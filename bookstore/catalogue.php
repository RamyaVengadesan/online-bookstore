<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Catalogue</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .modal-content {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            width: 500px;
            text-align: center;
            animation: slideIn 0.3s ease;
            border: 3px solid #8b7355;
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-content img {
            width: 180px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 3px solid #8b7355;
        }
        .close-btn {
            float: right;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            color: #c0392b;
        }
        .rating { color: #d4af37; font-size: 20px; }
        .stock-info { font-weight: bold; margin: 10px 0; }
        .in-stock { color: #27ae60; }
        .out-stock { color: #c0392b; }
    </style>
</head>
<body class="catalog-frame">
    <h1 class="catalog-title">BOOK CATALOGUE</h1>

    <?php
    require_once 'db_config.php';

    $dept = isset($_GET['dept']) ? $_GET['dept'] : 'all';

    $sql = "SELECT * FROM books";
    if ($dept != 'all') {
        $sql .= " WHERE category = '" . mysqli_real_escape_string($conn, $dept) . "'";
    }
    $sql .= " ORDER BY category, name";

    $result = mysqli_query($conn, $sql);
    ?>

    <table class="catalog-table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Book Details</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($book = mysqli_fetch_assoc($result)) {
                    $fullStars = floor($book['rating']);
                    $emptyStars = 5 - $fullStars;
                    $rating = str_repeat("&#9733;", $fullStars) . str_repeat("&#9734;", $emptyStars);
                    $stockClass = $book['stock'] > 0 ? 'in-stock' : 'out-stock';
                    $stockText = $book['stock'] > 0 ? 'In Stock (' . $book['stock'] . ')' : 'Out of Stock';
                    ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['name']); ?>" style="width: 80px; height: 100px; border-radius: 6px; border: 2px solid #8b7355;"></td>
                        <td style="text-align: left;">
                            <strong style="font-size: 18px; color: #3d2f1f;"><?php echo htmlspecialchars($book['name']); ?></strong><br>
                            <span style="color: #5d4e37;">Author: <?php echo htmlspecialchars($book['author']); ?></span><br>
                            <span style="color: #5d4e37;">Publisher: <?php echo htmlspecialchars($book['publisher']); ?></span><br>
                            <span class="rating"><?php echo $rating; ?></span> (<?php echo $book['rating']; ?>)
                        </td>
                        <td><strong style="color: #3d2f1f;">Rs <?php echo number_format($book['price'], 2); ?></strong></td>
                        <td><span class="<?php echo $stockClass; ?>"><?php echo $stockText; ?></span></td>
                        <td>
                            <?php if ($book['stock'] > 0) { ?>
                                <select id="qty-<?php echo $book['id']; ?>" style="padding: 8px; margin-bottom: 10px; border: 2px solid #d4c4a8; border-radius: 6px; font-family: Georgia, serif;">
                                    <?php for($i=1; $i<=5; $i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select><br>
                                <button onclick="addToCart(<?php echo $book['id']; ?>, '<?php echo addslashes($book['name']); ?>', <?php echo $book['price']; ?>)">Add to Cart</button><br>
                            <?php } else { ?>
                                <button disabled style="background: #ccc;">Out of Stock</button><br>
                            <?php } ?>
                            <button style="margin-top: 10px; background: #8b7355;" onclick="viewDetails(<?php echo htmlspecialchars(json_encode($book)); ?>)">View Details</button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="5" style="text-align: center; padding: 40px; color: #5d4e37;">No books found</td></tr>';
            }
            mysqli_close($conn);
            ?>
        </tbody>
    </table>

    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <img id="modalImg" src="" alt="Book">
            <h2 id="modalName"></h2>
            <p><strong>Author:</strong> <span id="modalAuthor"></span></p>
            <p><strong>Publisher:</strong> <span id="modalPublisher"></span></p>
            <p><strong>ISBN:</strong> <span id="modalISBN"></span></p>
            <p class="rating" id="modalRating"></p>
            <p class="stock-info" id="modalStock"></p>
            <p style="font-size: 24px; font-weight: bold; color: #5d4e37;">Rs <span id="modalPrice"></span></p>
        </div>
    </div>

    <script>
        function addToCart(id, name, price) {
            const qty = document.getElementById('qty-' + id).value;
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            
            for(let i = 0; i < qty; i++) {
                cart.push({id: id, name: name, price: price});
            }
            
            localStorage.setItem('cart', JSON.stringify(cart));
            alert(qty + ' x ' + name + ' added to cart!');
        }

        function viewDetails(book) {
            document.getElementById('modalImg').src = book.image;
            document.getElementById('modalName').textContent = book.name;
            document.getElementById('modalAuthor').textContent = book.author;
            document.getElementById('modalPublisher').textContent = book.publisher;
            document.getElementById('modalISBN').textContent = book.isbn;
            
            const fullStars = Math.floor(book.rating);
            const emptyStars = 5 - fullStars;
            const rating = "★".repeat(fullStars) + "☆".repeat(emptyStars);
            document.getElementById('modalRating').textContent = rating + ' (' + book.rating + ')';
            
            const stockText = book.stock > 0 ? 'In Stock (' + book.stock + ' available)' : 'Out of Stock';
            const stockClass = book.stock > 0 ? 'in-stock' : 'out-stock';
            document.getElementById('modalStock').textContent = stockText;
            document.getElementById('modalStock').className = 'stock-info ' + stockClass;
            
            document.getElementById('modalPrice').textContent = book.price.toFixed(2);
            document.getElementById('detailsModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('detailsModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>