{{-- Bootstrap CSS --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
<link rel="icon" href="/img/favicon_fpt.png" type="image/png">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!-- Bao gồm jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.2.0/html5-qrcode.min.js"></script>
@if (Session::has('error'))
<script>
    setTimeout(function() {
        Swal.fire({
            icon: 'error',
            title: 'Thất bại',
            text: `{{ Session::get('error') }}`,
            showConfirmButton: false,
            timer: 2000
        });
    }, 100);
</script>
@endif

@if (Session::has('success'))
<script>
    setTimeout(function() {
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: `{{ Session::get('success') }}`,
            showConfirmButton: false,
            timer: 2000
        });
    }, 100);
</script>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Bạn có chắc muốn xóa?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng bấm "Có", chuyển hướng tới route xóa
                window.location.href = '/xoa_thiet_bi/' + id;
            }
        });
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> {{-- Đảm bảo đã nhúng jQuery --}}
<script>
    $(document).ready(function() {
        // Lắng nghe sự kiện khi người dùng gõ vào ô tìm kiếm
        $("#searchInput").on("keyup", function() {
            // Lấy giá trị tìm kiếm và chuyển thành chữ thường
            var value = $(this).val().toLowerCase();

            // Lặp qua tất cả các hàng trong bảng (bỏ qua hàng tiêu đề)
            // Lấy cột IMEI (cột thứ 3, index 2)
            $("#thietBiTable tbody tr").filter(function() {
                var imeiText = $(this).children('td').eq(2).text().toLowerCase();

                // Ẩn hoặc hiện hàng dựa trên việc IMEI có chứa từ khóa tìm kiếm không
                $(this).toggle(imeiText.indexOf(value) > -1)
            });
        });
    });
</script>
<script>
    function onScanSuccess(qrCodeMessage) {
        // Hiển thị kết quả lên giao diện
        document.getElementById("result").innerHTML =
            '<span class="result">' + qrCodeMessage + "</span>";

        // Dò ID máy trong chuỗi, ví dụ "Máy 13-TSCT-Y25061700257"
        const match = qrCodeMessage.match(/Máy\s*(\d+)/i);
        if (match && match[1]) {
            const id = match[1]; // lấy số id (vd: 13)
            // Route đến trang chi tiết thiết bị
            window.location.href = '/chi_tiet_thiet_bi/' + id;
        } else {
            alert("Không tìm thấy ID trong mã QR!");
        }
    }
    // 13-TSCT-Y25061700257
    // When scan is unsuccessful fucntion will produce error message
    function onScanError(errorMessage) {
        // Handle Scan Error
    }
    // Setting up Qr Scanner properties
    var html5QrCodeScanner = new Html5QrcodeScanner("reader", {
        fps: 10,
        qrbox: 250
    });

    // in
    html5QrCodeScanner.render(onScanSuccess, onScanError);
</script>