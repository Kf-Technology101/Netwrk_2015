# DỰ ÁN NETWRK
## A. Vấn đề về  quá tải với Ratchet chat server
### 1. Đặt vấn đề
- Khi làm việc với __Chat server__ ,một trong những vấn đề khó được đặt ra là __server__ phải đối mặt với số lượng kết nối lớn và liên tục trong thời gian dài. Điề này dễ dẫn đến việc quá tải và __server__ sẽ tự động ngưng hoạt động.
- Hiện tại, khi ta deploy server chat trên Unix system, do cơ chế giới hạn số file đọc (~1024 files) nên Ratchet chỉ có thể mở được ~1024 kết nối cùng một lúc.
- Vấn đề tiếp theo là việc xử lý ngắt và tạo lại kết nối khi trình duyệt tắt bởi client. Hiện tại Ratchet hỗ trợ giải quyết vấn đề này qua WAMP component.
- Dưới đây sẽ trình bày chi tiết giải pháp và cách cài đặt server với Ratchet.
### 2. Giải pháp
#### a. Ngắt và khởi tạo kết nối bởi client
- Để ngắt và khởi tạo kết nối bởi client ta cần setup server side như sau:
    ```
    IoServer::factory(
        new HttpServer(new WsServer(new WampServer(new ChatSever))),
        2311,
        "0.0.0.0"
    );
    ```
    Tham khảo [http://socketo.me/docs/wamp](http://socketo.me/docs/wamp).
- Nguyên nhân cần sử dụng WAMP là do ở client chúng ta cần dùng  [Autobahn|JS](http://autobahn.ws/js/)
- Tiếp theo, ta cài đặt JS ở cllient như sau:
  * Thêm các library sau:
    * [https://github.com/cujojs/when](https://github.com/cujojs/when)
    * [http://autobahn.ws/js/](http://autobahn.ws/js/)
    * ở file ws.js ta cần code như sau:
    ```
    var conn = new ab.Session('ws://netwrk.net:2311',
        function() {
            conn.subscribe('kittensCategory', function(topic, data) {
                // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                console.log('New article published to category "' + topic + '" : ' + data.title);
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    ```
    Autobahn sẽ giúp tạo Session ở client với mỗi kết nối, giúp đánh dấu và kiểm tra kết nối có đang được connect hay không. Những thông tin này sẽ được gửi đến server qua cổng WAMPv2.
- ChartServer.php
  * Để xử lý dữ liệu được gửi lên qua WAMP  , ta cần code như sau:
  ```
    use Ratchet\ConnectionInterface as Conn;
    use Ratchet\Wamp\WampServerInterface;

    class BasicPubSub implements WampServerInterface {
        public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible) {
            $topic->broadcast($event);
        }

        public function onCall(Conn $conn, $id, $topic, array $params) {
            $conn->callError($id, $topic, 'RPC not supported on this demo');
        }

        // No need to anything, since WampServer adds and removes subscribers to Topics automatically
        public function onSubscribe(Conn $conn, $topic) {}
        public function onUnSubscribe(Conn $conn, $topic) {}

        public function onOpen(Conn $conn) {
        	echo "connected ".date('H:i:s d-m-Y'). "\n";
        }
        public function onClose(Conn $conn) {
        	echo "disconnected ".date('H:i:s d-m-Y')."\n";
        }
        public function onError(Conn $conn, \Exception $e) {}
    }
    ```