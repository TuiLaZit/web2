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
                            include("modules/nhanvien/nhanvien/them.php");
                            break;
                        case 'sua':
                            include("modules/nhanvien/nhanvien/sua.php");
                            break;
                        case 'xoa':
                            include("modules/nhanvien/nhanvien/xoa.php");
                            break;
                        default:
                            include("modules/nhanvien/nhanvien/lietke.php");
                    }
                } else {
                    include("modules/nhanvien/nhanvien/lietke.php");
                }
                break;
            case 'delivery':
                include("modules/delivery/list.php");
                break;
            
            // Quản lý khách hàng
            case 'customers':
                include("modules/customers/list.php");
                break;
            case 'customer_care':
                include("modules/customer_care/list.php");
                break;
            
            // Quản lý sản phẩm
            case 'products':
                include("modules/products/list.php");
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
                include("modules/orders/list.php");
                break;
                
            default:
                include("modules/dashboard.php");
        }
    } else {
        include("modules/dashboard.php");
    }
    ?>
</div>