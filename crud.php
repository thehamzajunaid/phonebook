<?php


//Connecting to a database 
$server = "127.0.0.1:3307";
$username = "root";
$password = "";
$database = "contacts";

// create a connection
$conn = mysqli_connect($server, $username, $password, $database);

// Die if connection was not successful
if (!$conn){
    die("Sorry we failed to connect " . mysqli_connect_error());
}

//Update the record in database if user has clicked "Save changes" in the edit modal(box)
if ($_SERVER["REQUEST_METHOD"] == 'POST'){
    if(isset($_POST['snoEdit'])){
        $sno = $_POST['snoEdit'];
        $name = $_POST['nameEdit'];
        $email = $_POST['emailEdit'];
        $number = $_POST['numberEdit'];

        $sql = "UPDATE `phonebook` SET `name` = '$name', `email` = '$email', `number` = '$number' WHERE `phonebook`.`id` = $sno";
        $result = mysqli_query($conn,$sql);
    
    }
    
}
// Inserts in db on clicking submit if new record is entered and the name field is not empty
if (isset($_POST['submit']) & !empty($_POST['name'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];

    $sql = "INSERT INTO `phonebook` (`name`, `email`, `number`) VALUES ('$name', '$email', '$number')";
    $result = mysqli_query($conn, $sql);
}

// deletes from db after user clicks delete button
if (isset($_GET['delete'])){
    $sno = $_GET['delete'];

    $sql = "DELETE FROM `phonebook` WHERE `id` = $sno";
    $result = mysqli_query($conn, $sql);
}




?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Phonebook</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
        <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="crudstyle.css">
    </head>

    <body>
        <!-- Modal -->
        <div class="modal fade" id="editModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Contact</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Modal Body -->
                        <form action="crud.php" method="POST">
                            <div class="form-group">
                                <input type="hidden" name="snoEdit" id="snoEdit">
                                <label for="nameEdit">Name</label>
                                <input type="text" class="form-control" name="nameEdit" id="nameEdit">
                            </div>
                            <div class="form-group">
                                <label for="emailEdit">Email address:</label>
                                <input type="email" class="form-control" name="emailEdit" id="emailEdit">
                            </div>
                            <div class="form-group">
                                <label for="numberEdit">Phone:</label>
                                <input type="number" class="form-control" name="numberEdit" id="numberEdit">
                            </div>
                            <button type="submit" name='submit' class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <!-- Main form -->
        <div class="container">
            <div>
                <h1 style='text-align: center;'>My Phonebook</h1>
                
            </div>
            <form action="crud.php" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>
                <div class="form-group">
                    <label for="number">Phone:</label>
                    <input type="number" class="form-control" name="number" id="number">
                </div>
                <button type="submit" name='submit' id='submit' class="btn btn-primary">Add Contact</button>
            </form>

        </div>

        <!-- Contacts list -->
        <div class="container">
            <h2>Saved Contacts</h2>
            <table class="table table-bordered" id='myTable'>
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone#</th>
                        <th> Actions </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = "SELECT * FROM phonebook";
                    $result = mysqli_query($conn, $sql);
                    $s = 0;
                    while ($row = mysqli_fetch_assoc($result)){
                        $s = $s + 1;
                        echo "<tr>
                        <th scope='row'>". $s."</th>
                        <td>" . $row['name']. "</td>
                        <td>" . $row['email'] ."</td>
                        <td>" . $row['number'] . "</td>
                        <td> <button name='edit' class='edit btn btn-primary' id=". $row['id'].">Edit</button>
                            <button name='delete' class='delete btn btn-danger' id=".$row['id'].">Delete</button>
                        </tr>"
                        ;
                    }
                    ?>


                </tbody>
            </table>
        </div>



    </body>
    <script>
        // enables datatables for better user interface for a table
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        // Adds on click listeners to every edit button which opens a modal for editing a contact
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit");
                tr = e.target.parentNode.parentNode;
                console.log(tr);
                // console.log(tr.getElementsByTagName("td")[0].textContent);
                name = tr.getElementsByTagName("td")[0].textContent
                email = tr.getElementsByTagName("td")[1].textContent;
                number = tr.getElementsByTagName("td")[2].textContent;
                console.log(name, email, number);
                nameEdit.value = name;
                emailEdit.value = email;
                numberEdit.value = number;
                snoEdit.value = e.target.id;
                // document.getElementById("editModal").setAttribute("data-toggle", "modal");
                // document.getElementById("editModal").setAttribute("data-target", "#editModal");
                $('#editModal').modal('toggle');

            })
        })

        // Adds on click listener on delete buttons and asks user if he is sure about it
        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("delete");
                sno = e.target.id;
                console.log(sno);
                if (confirm("Are you sure you want to delete this contact?")) {
                    console.log("yes");
                    window.location.assign(`crud.php?delete=${sno}`);
                }


            })
        })

        // when user clicks 'Add contact' is aks for confirmation
        submit = document.getElementById('submit');
        submit.addEventListener('click', () => {
            confirm("Ae you sure you want to add this contact?")
        })
    </script>

    </html>