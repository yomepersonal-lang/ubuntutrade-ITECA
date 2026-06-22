<?php

session_start();

include '../php/db_connect.php';
include '../php/auth.php';

if (!isSeller()) {
    echo "<p class='error'>Access denied. You must be a seller to edit listings.</p>";
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<p class='error'>Invalid product ID.</p>";
    exit;
}

// Fetch the listing - only if it belongs to this seller
$result = mysqli_query($conn, "SELECT * FROM listing WHERE listing_id=$id AND seller_id={$_SESSION['user_id']}");
$listing = mysqli_fetch_assoc($result);

if (!$listing) {
    echo "<p class='error'>Product not found or you don't have permission to edit it.</p>";
    exit;
}

$error_msg = '';
if (isset($_GET['error']) && $_GET['error'] === 'invalid_price') {
    $error_msg = 'Invalid price. Price must be greater than R0.00.';
}

$listing_id = $listing['listing_id'];
$title = htmlspecialchars($listing['title']);
$description = htmlspecialchars($listing['description']);
$price = $listing['price'];
$category = $listing['category'];
$status = $listing['status'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - UbuntuTrade</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .error-msg {
            color: #b33c3c;
            font-size: 0.8rem;
            margin-top: 4px;
            display: none;
        }
        .error-msg.show {
            display: block;
        }
        input.error {
            border-color: #b33c3c;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <h1><a href="../index.html">UbuntuTrade</a></h1>
        <div class="user-links">
            <a href="dashboard.html">Dashboard</a>
            <a href="../php/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <div class="back-button" style="margin-bottom:20px;">
            <button onclick="history.back()" class="btn-back">← Back</button>
        </div>
        <h2>Edit Product</h2>

        <?php if ($error_msg): ?>
            <p class="error" style="display:block;"><?php echo $error_msg; ?></p>
        <?php endif; ?>

        <form action="../php/seller_edit_listing.php" method="POST" id="editListingForm" style="max-width:600px;">
            <input type="hidden" name="id" value="<?php echo $listing_id; ?>">

            <label>Title</label>
            <input type="text" name="title" value="<?php echo $title; ?>" required>

            <label>Description</label>
            <textarea name="description" rows="4"><?php echo $description; ?></textarea>

            <label>Price (R)</label>
            <input type="number" step="0.01" name="price" id="price" value="<?php echo $price; ?>" required min="0.01">
            <div class="error-msg" id="priceError">Price must be greater than R0.00</div>

            <label>Category</label>
            <select name="category" required>
                <option value="">Select a category...</option>
                <option value="Electronics" <?php echo $category == 'Electronics' ? 'selected' : ''; ?>>Electronics</option>
                <option value="Clothing" <?php echo $category == 'Clothing' ? 'selected' : ''; ?>>Clothing</option>
                <option value="Home & Garden" <?php echo $category == 'Home & Garden' ? 'selected' : ''; ?>>Home & Garden</option>
                <option value="Beauty & Health" <?php echo $category == 'Beauty & Health' ? 'selected' : ''; ?>>Beauty & Health</option>
                <option value="Automotive" <?php echo $category == 'Automotive' ? 'selected' : ''; ?>>Automotive</option>
                <option value="Sports & Outdoors" <?php echo $category == 'Sports & Outdoors' ? 'selected' : ''; ?>>Sports & Outdoors</option>
                <option value="Toys & Games" <?php echo $category == 'Toys & Games' ? 'selected' : ''; ?>>Toys & Games</option>
                <option value="Other" <?php echo $category == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>

            <label>Status</label>
            <select name="status">
                <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="sold" <?php echo $status == 'sold' ? 'selected' : ''; ?>>Sold</option>
                <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>

            <button type="submit" style="margin-top:8px;">Update Listing</button>
        </form>
    </div>
    <div class="footer">
        <div class="footer-content">
            <div class="footer-left">
                &copy; 2026 UbuntuTrade – C2C for SA informal economy
            </div>
            <div class="footer-contact">
                <span class="contact-item">
                    <span class="icon">✉</span>
                    <a href="mailto:support@ubuntutrade.co.za">support@ubuntutrade.co.za</a>
                </span>
                <span class="contact-item">
                    <span class="icon">📞</span>
                    <span>012 234 4567</span>
                </span>
                <span class="contact-item">
                    <span class="icon">📍</span>
                    <span>Johannesburg, South Africa</span>
                </span>
            </div>
        </div>
    </div>
</div>

<script>
    // Price validation
    document.getElementById('editListingForm').addEventListener('submit', function(e) {
        const priceInput = document.getElementById('price');
        const price = parseFloat(priceInput.value);
        const errorMsg = document.getElementById('priceError');

        if (isNaN(price) || price <= 0) {
            e.preventDefault();
            priceInput.classList.add('error');
            errorMsg.classList.add('show');
            return false;
        } else {
            priceInput.classList.remove('error');
            errorMsg.classList.remove('show');
        }
        return true;
    });

    document.getElementById('price').addEventListener('input', function() {
        const price = parseFloat(this.value);
        const errorMsg = document.getElementById('priceError');
        if (!isNaN(price) && price > 0) {
            this.classList.remove('error');
            errorMsg.classList.remove('show');
        }
    });
</script>
</body>
</html>