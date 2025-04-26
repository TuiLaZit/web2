<?php
include(__DIR__ . '/../../admincp/config/config.php');
require_once(__DIR__.'/../../class/product_container.php');

$sql_get_top_products = "SELECT IdSP FROM sanpham WHERE Quantity > 0 AND Status = 1 ORDER BY ReleaseDate DESC LIMIT 5;";
$query_get_top_products = mysqli_query($mysqli, $sql_get_top_products);

if (!$query_get_top_products) {
    die("Lỗi truy vấn: " . mysqli_error($mysqli));
}

$products = [];

// Duyệt kết quả và tạo danh sách product_container
while ($row = mysqli_fetch_array($query_get_top_products)) {
    $product = product::getProductById($row['IdSP']);
    if ($product) {
        $products[] = $product;
    }
}
?>

<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($products as $index => $product) { ?>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="<?php echo $index; ?>"
                <?php echo $index == 0 ? ' class="active"' : ''; ?>
                aria-label="Slide <?php echo $index + 1; ?>">
            </button>
        <?php } ?>
    </div>

    <div class="carousel-inner" id="carouselInner">
        <?php foreach ($products as $index => $product) { ?>
            <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                <a href="pages/product-detail.php?id=<?php echo $product->id; ?>" class="banner-class">
                    <img src="/../../admincp/img/products/<?php echo $product->image; ?>" alt="<?php echo $product->name; ?>" class="banner-img">
                    <div class="banner-caption">
                        <h5 class="banner-text"><?php echo $product->name; ?></h5>
                        <p class="banner-text">Giá: <?php echo number_format($product->price, 0, ',', '.'); ?> VND</p>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>

    <button class="carousel-control-prev carousel-control" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next carousel-control" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>