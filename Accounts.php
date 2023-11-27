<?php
 @ include "DataBase.php";

 
 // Handle Delete action
 if (isset($_POST['deleteaccount']) && isset($_POST['delete'])) 
    {
     $id = $_POST['delete'];
 
     // Delete associated records in the 'agency' table
     $deletetransaction = "DELETE FROM transaction WHERE accountid = $id";
     if ($conn->query($deletetransaction) !== TRUE) {
         echo "Error deleting address: " . $conn->error;
     }
     
     // Delete the record from the 'agency' table
     $deleteAccounts = "DELETE FROM account WHERE accountid = $id";
     if ($conn->query($deleteAccounts) !== TRUE) {
         echo "Error deleting agency: " . $conn->error;
     }
     
 }
 

 ?>
 

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and stylesheets go here -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Gestionaire Bancaire</title>
    <style>
        header {
            filter: drop-shadow(4px 4px 5px rgba(255, 255, 255));
            border: 1px white solid;
        }
    </style>
</head>

<body>
    <section class="min-h-[95vh] w-[100vw] bg-white bg-cover">
        <header class="navbr w-[100%] h-[10vh]">
            <!-- Navigation bar goes here -->
            <nav class="h-[100%] flex gap-4 justify-center text-white items-center">
                <a href="index.php" class="hover:text-gray-200">Home</a>
                <a href="client.php" class="hover:text-gray-200">Client</a>
                <a href="compts.php" class="hover:text-gray-200">Compts</a>
                <a href="transaction.php" class="hover:text-gray-200">Transactions</a>
            </nav>
        </header>


        <div class="flex justify-evenly items-center">
   <h1 class="text-[55px] h-[10%] mb-[20px] text-center text-black">Accounts</h1>
   <a href="addaccounts.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">Add Account</a>

   </div>
        <?php
        // Check if the 'submit' and 'bankid' are set, indicating that the form is submitted
        if (isset($_POST['submit']) && isset($_POST['userid'])) {
            $userid = $conn->real_escape_string($_POST['userid']);

            // Fetch bank details based on the bankid
            $user_sql = "SELECT * FROM users WHERE userid = '$userid'";
            $user_result = $conn->query($user_sql);
           
            if ($user_result->num_rows > 0) {
                $user_row = $user_result->fetch_assoc();
                echo "<div class ='flex w-[100%]  justify-center h-[60px] border-[2px] border-black border-solid items-center text-black'>";
                echo "<p class='border-[2px] border-black border-solid w-[85%] h-[100%] flex items-center  justify-center'>Username : {$user_row["username"]}</p>";
                echo "<p class='border-[2px] border-black border-solid w-[85%] h-[100%] flex items-center  justify-center'>first Name : {$user_row["firstName"]}</p>";
                echo "<p class='border-[2px] border-black border-solid w-[85%] h-[100%] flex items-center  justify-center'>family Name : {$user_row["familyName"]}</p>";
                echo "</div>";
            }

            // Fetch data based on the selected bankid for 'agency'
            $sql = "SELECT * FROM `account` WHERE userid = '$userid'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<table class="leading-9 h-[90%]  w-[100%] text-center text-black">';
                echo '<thead>
                        <tr>
                            <th class="border-[2px] border-black border-solid w-[15%] ">ID</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">RIB</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Balance</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Edit</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Delete</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Transaction</th>
                        </tr>
                    </thead>';
                while ($row = $result->fetch_assoc()) {
                    echo '<form action="transaction.php" method="post" class="h-[10vh] items-start">';
                    echo "<tr>
                            <td class='border-[2px] border-black border-solid '>" . $row["accountId"] . " </td>
                            <td class='border-[2px] border-black border-solid '>" . $row["RIB"] . "  MAD</td>
                            <td class='border-[2px] border-black border-solid '> " . $row["balance"] . " </td>

                            <td class='border-[2px] border-black border-solid '>
                            <form action='users.php' method='post' class='height-[80px] cursor-pointer w-[100%] hover:bg-black bg-white hover:text-white text-black '>

                                <input type='hidden' name='accountid' value='" . $row["accountId"] . "'>
                                <input type='submit' name='users'  value='Users'>
                                </form>
                                </td>
                               

                            <td class='border-[2px] border-black border-solid '>
                            <form action='addaccounts.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                            <input type='hidden' name='operation' value='" . $row["accountId"]. "'>
                            <input type='hidden' name='accountid' value='" . $row["accountId"]. "'>
                            <input type='submit'  name='editing' value='Edit'>
                        </form>
                        
                            </td>
                            <td class='border-[2px] border-black border-solid '>
                            <form action='accounts.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                                <input type='hidden' name='delete' value='" . $row["accountId"] . "'>
                                <input type='submit'  name='deleteaccount' value='Delete'>
                            </form>
                        </td>
                        </tr>";
                }
                echo '</table>';
            } else {
                echo "<p class='text-center'>0 results</p>";
            }
        } else {
            // Handle the case when 'submit' and 'bankid' are not set (initial page load)
            // Fetch data for 'compts' table
            $sqlall = "SELECT * FROM `account`";
            $result2 = $conn->query($sqlall);
        
            if ($result2->num_rows > 0) {
                echo '<table class="leading-9  w-[100%] text-center h-[7vh] items-start text-black">';
                echo '<thead>
                        <tr>
                        <th class="border-[2px] border-black border-solid w-[15%] ">ID</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">RIB</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Balance</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Edit</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Delete</th>
                            <th class="border-[2px] border-black border-solid w-[15%] ">Transaction</th>
                        </tr>
                    </thead>';
                while ($row = $result2->fetch_assoc()) {
                
                    echo "<tr>
                    <td class='border-[2px] border-black border-solid '>" . $row["accountId"] . " </td>
                    <td class='border-[2px] border-black border-solid '> " . $row["RIB"] . "</td>
                    <td class='border-[2px] border-black border-solid '> " . $row["balance"] . "  MAD</td>


                            <td class='border-[2px] border-black border-solid '>
                            <form action='users.php' method='post' class='height-[80px] cursor-pointer w-[100%] hover:bg-black bg-white hover:text-white text-black '>

                                <input type='hidden' name='accountid' value='" . $row["accountId"] . "'>
                                <input type='submit' name='users'  value='Users'>
                                </form>
                                </td>
                               

                            <td class='border-[2px] border-black border-solid '>
                            <form action='addaccounts.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                            <input type='hidden' name='operation' value='" . $row["accountId"]. "'>
                            <input type='hidden' name='accountid' value='" . $row["accountId"]. "'>
                            <input type='submit'  name='editing' value='Edit'>
                        </form>
                        
                            </td>
                            <td class='border-[2px] border-black border-solid '>
                            <form action='accounts.php' method='post' class='height-[100%] cursor-pointer width-[100%] hover:bg-black bg-white hover:text-white text-black'>
                                <input type='hidden' name='delete' value='" . $row["accountId"] . "'>
                                <input type='submit'  name='deleteaccount' value='Delete'>
                            </form>
                        </td>
                        </tr>";
                }
                echo '</table>';
            } else {
                echo "<p class='text-center'>0 results</p>";
            }
        }
            $conn->close();
            ?>
    </section>

    <footer class="text-center h-[5vh] text-white bg-black flex items-center justify-center">
        <h2 >Copyright © 2030 Hashtag Developer. All Rights Reserved</h2>
    </footer>
</body>

</html>