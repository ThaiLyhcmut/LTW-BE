</div>


<script>
function deleteDB(url, songId) {
    if (!confirm('Bạn có chắc chắn muốn xóa record này?')) {
        return; // Hủy nếu người dùng không xác nhận
    }
    token = localStorage.getItem("auth_token")
    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            // Nếu cần thêm token xác thực, ví dụ:
            'Authorization': 'Bearer ' + token
        },
        
        body: JSON.stringify({
          id: songId,
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Xóa record thất bại');
        }
        return response.json();
    })
    .then(data => {
        alert('Xóa record thành công!');
        // Xóa hàng khỏi bảng mà không cần tải lại trang
        document.getElementById(songId).parentElement.remove();
    })
    .catch(error => {
        console.error('Lỗi:', error);
        alert('Đã có lỗi xảy ra khi xóa record.');
    });
}
</script>
<script src="/public/assets/static/js/components/dark.js"></script>
<script src="/public/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>


<script src="/public/assets/compiled/js/app.js"></script>



<!-- Need: Apexcharts
<script src="/public/assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="/public/assets/static/js/pages/dashboard.js"></script> -->

</body>

</html>