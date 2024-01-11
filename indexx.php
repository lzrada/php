<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "try";

$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("koneksi gagal");
}

// Handle Edit and Delete actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'edit') {
        // Implement edit logic here
        // Retrieve data by id, populate form fields for editing
        $query_edit = "SELECT * FROM mahasiswa WHERE id = $id";
        $result_edit = mysqli_query($conn, $query_edit);

        if ($result_edit) {
            $data_edit = mysqli_fetch_assoc($result_edit);
            // Populate form fields with $data_edit for editing
            $nama_edit = $data_edit['nama'];
            $nim_edit = $data_edit['nim'];
            $semester_edit = $data_edit['semester'];
            $alamat_edit = $data_edit['alamat'];
            echo "Sedang dalam mode edit. Silakan ubah data di formulir.";
        } else {
            echo "Gagal mengambil data untuk diedit.";
        }
    } elseif ($action == 'delete') {
        // Implement delete logic here
        $query_delete = "DELETE FROM mahasiswa WHERE id = $id";
        $result_delete = mysqli_query($conn, $query_delete);

        if ($result_delete) {
            echo "Data berhasil dihapus.";
            header("Location: indexx.php"); 
            exit(); 
        } else {
            echo "Gagal menghapus data.";
        }
    }
}

if (isset($_POST['submit'])) {
    // Mendapatkan data dari formulir
    $nama = $_POST['username'];
    $nim = $_POST['nim'];
    $semester = $_POST['semester'];
    $alamat = $_POST['alamat'];

    if (isset($_POST['edit_id'])) {
        // Edit existing data
        $edit_id = $_POST['edit_id'];
        $query_update = "UPDATE mahasiswa SET nama='$nama', nim='$nim', semester='$semester', alamat='$alamat' WHERE id=$edit_id";
        $result_update = mysqli_query($conn, $query_update);

        if ($result_update) {
            echo "Data berhasil diperbarui.";
            header("Location: indexx.php"); // Redirect to refresh the page
            exit(); // Exit to avoid further execution of the script
        } else {
            echo "Gagal memperbarui data.";
        }
    } else {
        // Menyimpan data ke database
        $query_insert = "INSERT INTO mahasiswa (nama, nim, semester, alamat) VALUES ('$nama', '$nim', '$semester', '$alamat')";
        $result_insert = mysqli_query($conn, $query_insert);

        if ($result_insert) {
            echo "Data berhasil disimpan.";
        } else {
            echo "Gagal menyimpan data.";
        }
    }
}

// Menampilkan data mahasiswa dari database
$query_select = "SELECT * FROM mahasiswa";
$result_select = mysqli_query($conn, $query_select);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form pendaftaran</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<form action="indexx.php" method="POST">
    <fieldset>
        <legend> Form Pendaftaran</legend>
        <p>
            <label>Nama Mahasiswa:</label>
            <input type="text" name="username" placeholder="Nama..." value="<?php echo isset($nama_edit) ? $nama_edit : ''; ?>" />
        </p>
        <p>
            <label>NIM :</label>
            <input type="text" name="nim" placeholder="nim..." value="<?php echo isset($nim_edit) ? $nim_edit : ''; ?>" />
        </p>
        <p>
            <label> Semester :</label>
            <select name="semester" class="form-select" aria-label="Default select example">
                <?php
                $semesters = [1, 2, 3, 4, 5, 6, 7, 8];
                foreach ($semesters as $s) {
                    echo "<option value=\"$s\"";
                    if (isset($semester_edit) && $semester_edit == $s) {
                        echo " selected";
                    }
                    echo ">$s</option>";
                }
                ?>
            </select>
        </p>
        <p>
            <label> alamat :</label>
            <input type="text" name="alamat" placeholder="Alamat...." value="<?php echo isset($alamat_edit) ? $alamat_edit : ''; ?>">
        </p>
        <p>
            <?php if (isset($nama_edit)) : ?>
                <input type="hidden" name="edit_id" value="<?php echo $id; ?>">
                <input type="submit" name="submit" value="Update" />
            <?php else : ?>
                <input type="submit" name="submit" value="Daftar" />
            <?php endif; ?>
        </p>
    </fieldset>
</form>

<h1>Data Mahasiswa</h1>
<table border="1">
    <tr>
        <th>Nama</th>
        <th>NIM</th>
        <th>Semester</th>
        <th>Alamat</th>
        <th>Aksi</th>
    </tr>
    <?php
    while ($row = mysqli_fetch_assoc($result_select)) {
        echo "<tr>";
        echo "<td>" . $row['nama'] . "</td>";
        echo "<td>" . $row['nim'] . "</td>";
        echo "<td>" . $row['semester'] . "</td>";
        echo "<td>" . $row['alamat'] . "</td>";
        echo "<td><a href='indexx.php?action=edit&id=" . $row['id'] . "'>Edit</a></td>";
        echo "<td><a href='indexx.php?action=delete&id=" . $row['id'] . "'>Delete</a></td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
