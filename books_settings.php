<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION["type"]) || (isset($_SESSION["type"]) && $_SESSION['type'] == 'admin' )) header('Location: ./index.php'); 

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "Books Settings";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Books Settings</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>
    <section class="wrapper">
    <?php
        if (isset($_SESSION["addbook"])) { ?>

            <section id="booksList" class="article">

                <h1> Add New Book</h1>

                <form action="Control/BookControl.php" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <th><label for="title">title:</label></th>
                            <td><input type="text" name="title" id="title" size="30" maxlength="30"></td>
                        </tr>

                        <tr>
                                <th><label for="Photo"> Photo:</label></th>
                                <td><input type="file" name="photo" size="30" maxlength="30"></td>
                        </tr>
                        

                        <tr>
                                <th><label for="price"> price:</label></th>
                                <td><input type="number" min='0.01' value='0.01' step="0.01" name="price" size="30" maxlength="30"></td>
                        </tr>


                        <tr>	
                            <th><label for="available">Status:</label> </th>
                            <td style="width: 100%;">
                                <select name="status" id="available" style="width: 100%;">
                                    <option value="available" selected>Available</option>
                                    <option value="not available">Not Available</option>
                                </select>
                            </td>
                        </tr>

                        <tr>	
                            <th><label for="Condtions">Condition:</label> </th>
                            <td style="width: 100%;">
                                <select name="condition" id="Condtions" style="width: 100%;">
                                    <option value="new" selected>New</option>
                                    <option value="used">used</option>
                                </select>
                            </td>
                        </tr>

                        
                        

                        <tr>
                            <th><label for="courses">choose Multi Corses :</label> </th>
                            <td>
                            <select name="courses[]" style="width: 100%;" multiple>
                                    <?php
                                        foreach($_SESSION['courses'] as $course) { ?>
                                            <option value ='<?php echo $course["id"] ?>'><?php echo $course['name'] ?></option> 
                                     <?php }?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th><label for="desc"> Description:</label></th>
                            <td><textarea rows="10" cols="60" name="desc" id="desc"></textarea>
                                <script>
                                    ClassicEditor
                                        .create(document.querySelector('#desc'))
                                        .catch(error => {
                                            console.error(error);
                                        });
                                </script>
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" style="text-align: center;"><input type="submit" name="CompleteAddbook"
                                                                            class="button button2" value="Add book">
                            </td>
                        </tr>
                    </table>

                </form>
            </section>

            <?php
            unset($_SESSION["showbooks"]);
        } else { if (isset($_SESSION["editbook"])) { ?>

                <section id="booksList" class="article">

                    <h1>Update book (<?php
                        echo $_SESSION['title'] ?>) </h1>

                    <form action="Control/bookControl.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="book_id" value="<?php
                        echo $_SESSION['book_id'] ?>">
                        <table>
                            <tr>
                                <th><label for="title">title:</label></th>
                                <td><input type="text" name="title" value="<?php
                                    echo $_SESSION['title'] ?>" size="30" maxlength="30"></td>
                            </tr>

                            <tr>
                                <th><label for="Photo"> Photo:</label></th>
                                <td><input type="file" name="photo" size="30" maxlength="30"></td>
                            </tr>
                            

                            <tr>
                                    <th><label for="price"> price:</label></th>
                                    <td><input type="number" min='0.01' value='<?php echo $_SESSION['price']?>' name="price" size="30" maxlength="30"></td>
                            </tr>


                            <tr>	
                                <th><label for="available">Status:</label> </th>
                                <td style="width: 100%;">
                                    <select name="status" id="available" style="width: 100%;">
                                        <option value="available" <?php if($_SESSION['status'] == 'available') echo 'selected' ?>>Available</option>
                                        <option value="not available" <?php if($_SESSION['status'] == 'not available') echo 'selected' ?>>Not Available</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>	
                                <th><label for="Condtions">Condition:</label> </th>
                                <td style="width: 100%;">
                                    <select name="condition" id="Condtions" style="width: 100%;">
                                        <option value="new" <?php if($_SESSION['condition'] == 'new') echo 'selected' ?>>new</option>
                                        <option value="used" <?php if($_SESSION['condition'] == 'used') echo 'selected' ?>>used</option>
                                   </select>
                                </td>
                            </tr>

                            <tr>
                                <th><label for="courses">choose multi course:</label> </th>
                                <td>
                                    <select name="courses[]" style="width: 100%;" multiple>
                                        <?php
                                            foreach($_SESSION['courses'] as $course) {
                                                $flag = false;
                                                foreach($_SESSION['book_courses'] as $c) {
                                                    if($course['id'] == $c['course_id']) {
                                                        $flag = true;
                                                    }
                                                }
                                                if($flag == true)
                                                    echo "<option value='{$course["id"]}' selected>{$course['name']}</option>";
                                                else
                                                    echo "<option value='{$course["id"]}'>{$course['name']}</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="desc"> Description:</label></th>
                                <td><textarea rows="10" cols="60" name="desc" id="desc"><?php
                                        echo $_SESSION['desc']; ?></textarea>
                                    <script>
                                        ClassicEditor
                                            .create(document.querySelector('#desc'))
                                            .catch(error => {
                                                console.error(error);
                                            });
                                    </script>
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan="2" style="text-align: center;"><input type="submit" name="CompleteEditbook"
                                                                                class="button button2" value="Edit book">
                                </td>
                            </tr>
                        </table>

                    </form>

                </section>

                <?php
                unset($_SESSION["showbooks"]);
        } else { ?>

                <section id="" class="article">

                    <h1> books List <a class="button button2" href="Control/bookControl.php?addbook">Add book</a></h1>
                    <table id="table">
                        <tr>
                            <th>book ID</th>
                            <th>Book Photo</th>
                            <th>Book title</th>
                            <th>Book Status</th>
                            <th>book Condition</th>
                            <th>book price</th>
                            <th>book description</th>
                            <th>book courses</th>
                            <th>Control</th>
                        </tr>

                        <?php
                        if(isset( $_SESSION["books_num"])) {
                        for ($i = 1; $i <= $_SESSION["books_num"]; $i++) {
                            $var_book_id = "book_" . $i . "_id";
                            $var_book_photo = "book_" . $i . "_photo";
                            $var_book_title = "book_" . $i . "_title";
                            $var_book_status = "book_" . $i . "_status";
                            $var_book_condition = "book_" . $i . "_condition";
                            $var_book_price = "book_" . $i . "_price";
                            $var_book_description = "book_" . $i . "_description";
                            $var_book_courses = "book_" . $i . "_courses";

                            ?>

                            <form action="Control/bookControl.php" method="post" enctype="multipart/form-data">
                                <tr>
                                    <td> <?php
                                        echo $_SESSION[$var_book_id]; ?> </td>
                                     <td> 
                                        <?php if($_SESSION[$var_book_photo] != '') {?>
                                            <img src="<?php echo $_SESSION[$var_book_photo]; ?>" alt="">
                                        <?php } else {?>
                                            <img src="./imgs/books/default.png" alt="">
                                        <?php }?>
                                        
                                    </td>
                                    <td> <?php
                                        echo $_SESSION[$var_book_title]; ?> </td>
                                    <td> <?php
                                        echo $_SESSION[$var_book_status]; ?> </td>
                                    <td> <?php
                                        echo $_SESSION[$var_book_condition]; ?> </td>
                                    <td> <?php
                                        echo $_SESSION[$var_book_price]; ?> </td>
                                    <td><p><?php
                                            echo htmlspecialchars_decode(stripslashes($_SESSION[$var_book_description])); ?></p></td>
                                    <td>
                                        <?php 
                                            foreach($_SESSION[$var_book_courses] as $course) {
                                                echo "<span>{$course['course_name']}</span><br/>";
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <input type="hidden" name="book_id" value=" <?php
                                        echo $_SESSION[$var_book_id]; ?> ">
                                        <input type="submit" name="editbook" value="Edit book">
                                        <input type="submit" name="deletebook" value="Delete book">
                                    </td>
                                </tr>
                            </form>

                            <?php
                        } } ?>

                    </table>
                </section>
                <?php
            }
        } ?>

    </section>
    
    <?php include 'layout/footer.php'; ?>

</body>

</html>