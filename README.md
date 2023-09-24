# **Ассинхронное шифрование на Laravel с openssl**

## Публикация "config/openssl.php", "resources/views/vendor/openssl"

    php artisan vendor:publish go280286sai\laravel_openssl\Providers\OpensslProvider

### После установке пакета необходимо выполнить миграцмю:

    php artisan migrate

## Генерация публичного ключа и получение данных

### Для ручной генерации нового ключа необходимо выполнить:

    php artisan openssl:new

### Для получения персональных данных:

    php artisan:show

-----BEGIN PUBLIC KEY-----

"----------------------------------"

-----END PUBLIC KEY-----

Personal key: *************

Полученные данные нужны для передачи нам информации!

Изменить ключь можно в /vendor/go280286sai/laravel_openssl/src/Models/Ssl_search

    public static string $ssl_public_key = '*************';

### Создадим контроллер для работы с ресурсами:

    php artisan make:controller OpensslController -r

## Для отображения всех доступных ресурсов добавим метод: 

    public function index()
    {
        $resource = Ssl_search::all();
        return view('vendor.openssl.index', ['resource' => $resource]);
    }

## Для добавления ресурса содаем контроллер и добавляем методы:

    public function create(): View
    {
         return view('vendor.openssl.add_resource');
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'key' => 'required|string',
            'url' => 'required|string',
        ]);
        Ssl_search::add_resource(['name' => $request->name, 'key' => $request->key, 'url' => $request->url]);

        return redirect()->back();
    }

Имя и адресс ресурса будет добавлен в базу данных 'ssl_searches', а public key будет создан под именем полученного id
в формате 1_id_public.pem по адрессу "/vendor/go280286sai/laravel_openssl/src/OpenSSL/files/ssl"

## Для обновления ресурса:

    public function edit(int $id)
    {
        $resource = Ssl_search::find($id);
        $publicKey = Ssl_search::get_public_key($id . '_id_public');
        return view('vendor.openssl.update_resource', ['resource' => $resource, 'publicKey' => $publicKey]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string',
            'key' => 'required|string',
            'url' => 'required|string',
        ]);
        Ssl_search::update_resource(['name' => $request->name, 'key' => $request->key, 'url' => $request->url], $id);
        return redirect()->back();
    }

## Для удаления:

    public function destroy(int $id)
    {
        Ssl_search::remove($id);
        return redirect()->back();
    }

## Передача зашиврованной информации:

    public function message()
    {
        $resource = Ssl_search::all();
        return view('vendor.openssl.send_message', ['resource' => $resource]);
    }

    public function send_message(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'text' => 'required|string'
        ]);
        $id = $request->id;
        $text = $request->text;
        $url = Ssl_search::find($id)->url;
        $key = Ssl_search::get_public_key($id . '_id_public');
        $send = Ssl_search::encrypt($text, $key);
        $name = Ssl_search::$ssl_public_key;
        try {
            $result = Http::post($url, ['name'=>$name, 'text'=>$send]);
            return $result->body();
            if ($result->status() == 200) {
                return redirect()->back();
            }
        }
        catch (\Throwable $th) {
            LogMessage::send('error', $th->getMessage());
            return redirect()->back();
        }
    }
С формы передаем id и текст сообщения. По id получаем url для отправки.

$name = Ssl_search::$ssl_public_key - имя ключа системы, с которой будет отправлятся сообщение.

$key = Ssl_search::get_public_key($id . '_id_public') - загрузит public key данного ресурса.

$send = Ssl_search::encrypt($text, $key) - выполнит кодирование сообщение и добавит подпись.

$result = Http::post($url, ['name'=>$name, 'text'=>$send]) - передача данных.

## Добавленные роуты:

    Route::resource('/resource', OpensslController::class);
    Route::get('/ssl/message', [OpensslController::class, 'message']);
    Route::post('/ssl/send_message', [OpensslController::class, 'send_message']);

## Получение сообщения:

В /vendor/go280286sai/laravel_openssl/src/Http/Controllers/OpenSslController:

    public function index(Request $request): JsonResponse|string
    {
    $request->validate([
        'name'=> 'required|string',
        'text' => 'required|string',
    ]);
    $id = Ssl_search::where('name', $request->name)->first('id');
    if(!empty($id)){
        $data = $request->text;
        $data[0]=trim($data[0]);
        $data[1]=trim($data[1]);
        return Ssl_search::decrypt($data, Ssl_search::get_public_key($id['id'].'_id_public'));
    } else{
         return Response::json(['message' => 'Not found'], 404);
         }
    }

По переданному имени ресурса в базе находим его id и передаем его и массив $data в фунцию:

    Ssl_search::decrypt($data, Ssl_search::get_public_key($id['id'].'_id_public'));

Выполняется расшифровка сообщения и проверка подписи. Если подпись верна то возвращается результат.

