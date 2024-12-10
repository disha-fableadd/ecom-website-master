<?php
include 'db.php';

$products_per_page = 6;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max($page, 1); 

$offset = ($page - 1) * $products_per_page;

$price_filter = isset($_GET['price']) ? $_GET['price'] : null;
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : '';

$sql = "SELECT * FROM products";
$conditions = [];

// Apply price filter if not 'all'
if (!empty($price_filter) && $price_filter !== 'all') {
    switch ($price_filter) {
        case '0-100':
            $conditions[] = "price BETWEEN 0 AND 100";
            break;
        case '100-200':
            $conditions[] = "price BETWEEN 100 AND 200";
            break;
        case '200-300':
            $conditions[] = "price BETWEEN 200 AND 300";
            break;
        case '300-400':
            $conditions[] = "price BETWEEN 300 AND 400";
            break;
        default:
            die("Invalid price filter");
    }
}


// Apply search filter
if (!empty(trim($search_keyword))) {
    $conditions[] = "name LIKE '%" . $conn->real_escape_string($search_keyword) . "%'";
}

// Apply category filter if present
if (!empty($sort_option) && preg_match('/category_(.+)/', $sort_option, $matches)) {
    $category = $matches[1];
    $conditions[] = "category = '" . $conn->real_escape_string($category) . "'";
}

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Sorting condition (if applicable)
if (!empty($sort_option)) {
    $sql .= " ORDER BY id";
}

// Pagination
$sql .= " LIMIT $products_per_page OFFSET $offset";

$result = $conn->query($sql);
if (!$result) {
    die("Query Error: " . $conn->error . "<br>SQL: " . $sql);
}

// Get the total number of products that match the filters
$total_products_sql = "SELECT COUNT(*) AS total FROM products";
if (count($conditions) > 0) {
    $total_products_sql .= " WHERE " . implode(" AND ", $conditions);
}

$total_products_result = $conn->query($total_products_sql);
if (!$total_products_result) {
    die("Total Products Query Error: " . $conn->error . "<br>SQL: " . $total_products_sql);
}
$total_products_row = $total_products_result->fetch_assoc();
$total_products = $total_products_row['total'];

$total_pages = ceil($total_products / $products_per_page);
?>

<?php include 'header.php'; ?>

<!-- Shop Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <!-- Shop Sidebar Start -->
        <div class="col-lg-3 col-md-12">
            <!-- Price Filter Start -->
            <div class="border-bottom mb-4 pb-4">
                <h5 class="font-weight-semi-bold mb-4">Filter by price</h5>
                <form method="GET" id="filter-form">
                    <div>
                        <input type="radio" id="price-all" name="price" value="all" 
                            <?php echo (empty($price_filter) || $price_filter === 'all') ? 'checked' : ''; ?>>
                        <label for="price-all">All Prices</label>
                    </div>
                    <div>
                        <input type="radio" id="price-1" name="price" value="0-100" 
                            <?php echo ($price_filter === '0-100') ? 'checked' : ''; ?>>
                        <label for="price-1">$0 - $100</label>
                    </div>
                    <div>
                        <input type="radio" id="price-2" name="price" value="100-200" 
                            <?php echo ($price_filter === '100-200') ? 'checked' : ''; ?>>
                        <label for="price-2">$100 - $200</label>
                    </div>
                    <div>
                        <input type="radio" id="price-3" name="price" value="200-300" 
                            <?php echo ($price_filter === '200-300') ? 'checked' : ''; ?>>
                        <label for="price-3">$200 - $300</label>
                    </div>
                    <div>
                        <input type="radio" id="price-4" name="price" value="300-400" 
                            <?php echo ($price_filter === '300-400') ? 'checked' : ''; ?>>
                        <label for="price-4">$300 - $400</label>
                    </div>
                </form>
            </div>
            <!-- Price Filter End -->
        </div>
        <!-- Shop Sidebar End -->

        <!-- Shop Product Start -->
        <div class="col-lg-9 col-md-12">
            <div class="row pb-3">
                <div class="col-12 pb-1">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <!-- Search Form -->
                        <form method="GET" id="searchForm">
                            <div class="input-group">
                                <!-- Search Input -->
                                <input type="text" class="form-control" id="searchName" name="search" value="<?php echo $search_keyword; ?>" placeholder="Search by name">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text bg-transparent text-primary">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden fields to keep price and category filters when submitting the form -->
                            <input type="hidden" name="price" value="<?php echo $price_filter; ?>">
                            <input type="hidden" name="category" value="<?php echo $category_filter; ?>">
                        </form>


                        <!-- Sort by Dropdown -->
                        <div class="dropdown ml-4">
                            <button class="btn border dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Sort by
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="triggerId">
                                <a class="dropdown-item" href="?page=1&price=<?php echo $price_filter; ?>&search=<?php echo $search_keyword; ?>&sort=all_categories">All Categories</a>
                                <?php
                                // Fetch categories from the database and display them
                                $category_sql = "SELECT DISTINCT category FROM products";
                                $category_result = $conn->query($category_sql);
                                if ($category_result->num_rows > 0) {
                                    while ($category = $category_result->fetch_assoc()) {
                                        echo '<a class="dropdown-item" href="?page=1&price=' . $price_filter . '&search=' . $search_keyword . '&sort=category_' . $category['category'] . '">' . $category['category'] . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Products -->
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                            <div class="card product-item border-0 mb-4">
                                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                    <img class="img-fluid" style="height:300px;width:300px" src="uploads/<?php echo $row['image']; ?>" alt="Product">
                                </div>
                                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                    <h6 class="text-truncate mb-3"><?php echo $row['name']; ?></h6>
                                    <div class="d-flex justify-content-center">
                                        <h6>$<?php echo $row['price']; ?></h6>
                                    </div>
                                </div>
                                <div class="card-footer mx-auto">
                                    <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm text-dark p-0">
                                        <i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                   
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-center'>No products found that match your filters.</p>";
                }
                ?>
            </div>

            <!-- Pagination -->
            <div class="col-12 pb-1">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-3">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&price=<?php echo $price_filter; ?>&search=<?php echo $search_keyword; ?>&sort=<?php echo $sort_option; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>


                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&price=<?php echo $price_filter; ?>&search=<?php echo $search_keyword; ?>&sort=<?php echo $sort_option; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php } ?>
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&price=<?php echo $price_filter; ?>&search=<?php echo $search_keyword; ?>&sort=<?php echo $sort_option; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Shop Product End -->
    </div>
</div>
<!-- Shop End -->

<?php include 'footer.php'; ?>

<script>
    // Automatically submit the form when a radio button is clicked
    document.querySelectorAll('#filter-form input[type="radio"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.getElementById('filter-form').submit();
        });
    });
</script>
