<div class="main-content">
    <?php
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        switch($action) {
            // Quản lý nhân viên
            case 'staff':
                if(isset($_GET['query'])) {
                    $query = $_GET['query'];
                    switch($query) {
                        case 'them':
                            include("modules/nhanvien/them.php");
                            break;
                        case 'sua':
                            include("modules/nhanvien/sua.php");
                            break;
                        case 'xoa':
                            include("modules/nhanvien/xoa.php");
                            break;
                        default:
                            include("modules/nhanvien/lietke.php");
                    }
                } else {
                    include("modules/nhanvien/lietke.php");
                }
                break;
            case 'delivery':
                include("modules/delivery/list.php");
                break;
            
            // Quản lý khách hàng
            case 'customers':
                include("modules/customers/danh-sach.php");
                break;
            case 'customer_care':
                include("modules/customer_care/list.php");
                break;
            
            // Quản lý sản phẩm
            case 'products':
                include("modules/products/danh-sach-san-pham.php");
                break;
            case 'add-product':
                include("modules/products/them-san-pham.php");
                break;
            
            case 'imports':
                include("modules/products/import/list-import.php");
                break;

            case 'groups':
                include("modules/groups/list.php");
                break;
            case 'members':
                include("modules/members/list.php");
                break;
            case 'versions':
                include("modules/versions/list.php");
                break;
            case 'promotions':
                include("modules/promotions/list.php");
                break;
            
            // Quản lý hóa đơn
            case 'orders':
                $query = isset($_GET['query']) ? $_GET['query'] : '';
                
                switch ($query) {
                    case 'details':
                        include 'orders/chi-tiet-don-hang.php';
                        break;
                    default:
                        include 'orders/danh-sach-don-hang.php';
                        break;
                }
                break;
                
            default:
                include("modules/statistics.php");
        }
    } else {
        include("modules/statistics.php");
    }
    ?>
</div>
