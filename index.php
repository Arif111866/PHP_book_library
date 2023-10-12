<?php
// Define the file path
$filePath = 'books.json';

// Function to read books from the JSON file
function readBooks() {
    global $filePath;
    if (file_exists($filePath)) {
        $json = file_get_contents($filePath);
        return json_decode($json, true);
    }
    return [];
}

// Function to save books to the JSON file
function saveBooks($books) {
    global $filePath;
    $json = json_encode($books, JSON_PRETTY_PRINT);
    file_put_contents($filePath, $json);
}

// Function to add a new book
function addBook($book) {
    $books = readBooks();
    $books[] = $book;
    saveBooks($books);
}

// Function to delete a book by ID
function deleteBook($id) {
    $books = readBooks();
    foreach ($books as $key => $book) {
        if ($book['id'] == $id) {
            unset($books[$key]);
            saveBooks($books);
            break;
        }
    }
}

// Function to search for books by title
function searchBooks($query) {
    $books = readBooks();
    $results = [];
    foreach ($books as $book) {
        if (stripos($book['title'], $query) !== false) {
            $results[] = $book;
        }
    }
    return $results;
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['operation'] == 'add'){
        $book = [
            'title' => $_POST['title'],
            'author_name' => $_POST['author_name'],
            'id' => (int) $_POST['id'],
            'price' => (int) $_POST['price']
        ];
        addBook($book);
        $books = readBooks();
    }else{
        deleteBook($_POST['id']) ;
        $books = readBooks();
    }
    
}
else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['operation']) && $_GET['operation'] == 'Search')
{
    $books = readBooks();
    echo '<table border="1">
    <tr>
        <th>Book Title</th>
        <th>Author Name</th>
        <th>ID</th>
        <th>Price</th>
    </tr>';
    foreach ($books as $book) {
        if (stripos($book['title'], $_GET['title']) !== false){
            echo '<tr>';
            echo '<td>' . $book['title'] . '</td>';
            echo '<td>' . $book['author_name'] . '</td>';
            echo '<td>' . $book['id'] . '</td>';
            echo '<td>' . $book['price'] . '</td>';
            echo '<\tr>' ;
        }
    }
} 
else {
    $books = readBooks();
}   
// page formate by html code

?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Library</title>
</head>
<body>
    <h1>Book Library</h1>

    <!-- Add a form to add new books and delete books -->
    <form method="post" action="">
        <label for="title">Book Title---: </label>
        <input type="text" name="title" required><br> <br>

        <label for="author_name">Author Name:</label>
        <input type="text" name="author_name" required><br><br>

        <label for="id">ID ------------:</label>
        <input type="text" name="id" required><br><br>

        <label for="price">Price----------:</label>
        <input type="text" name="price" required><br><br>
        <input type="text" name="operation" value="add" hidden>

        <input type="submit" value="Add Book"><br><br>
    </form>
    <!-- delete a book -->
    <form method="post" action="">
        <label for="id">ID ------------:</label>
        <input type="text" name="id" required><br><br>
        <input type="text" name="operation" value="delete" hidden>
        <input type="submit" value="Delete by ID"><br><br>
    </form>

    <!-- Search form -->
    <form method="get" action="">
        <h4>You can search Book here by Book title</h2>  
        <input type="hidden" name="operation" value="Search">
        <input type="text" name="title" placeholder="Search by title">
        <input type="submit" value="Search"> <br><br><br>
    </form>

   <!-- Display the list of books in a table -->
<table border="1">
    <tr>
        <th>Book Title</th>
        <th>Author Name</th>
        <th>ID</th>
        <th>Price</th>
    </tr>
    <?php foreach ($books as $book): ?>
        <tr>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author_name']; ?></td>
            <td><?php echo $book['id']; ?></td>
            <td><?php echo $book['price']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
