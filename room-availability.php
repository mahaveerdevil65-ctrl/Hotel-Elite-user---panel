<?php
$conn = mysqli_connect("localhost", "root", "", "mahavir");

if (!$conn)
	{
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['add_room'])) 
 {
    $room_no = mysqli_real_escape_string($conn, $_POST['room_no']);
    $floor = mysqli_real_escape_string($conn, $_POST['floor']);
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $room_status = mysqli_real_escape_string($conn, $_POST['room_status']);
    
    $query = "INSERT INTO rooms (room_no, floor, room_type, room_status) VALUES ('$room_no', '$floor', '$room_type', '$room_status')";
    mysqli_query($conn, $query);
    header("Location: room-availability.php");
    exit();
}

if (isset($_POST['update_room'])) 
 {
    $id = (int)$_POST['room_id'];
    $room_no = mysqli_real_escape_string($conn, $_POST['room_no']);
    $floor = mysqli_real_escape_string($conn, $_POST['floor']);
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $room_status = mysqli_real_escape_string($conn, $_POST['room_status']);
    
    $query = "UPDATE rooms SET room_no='$room_no', floor='$floor', room_type='$room_type', room_status='$room_status' WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: room-availability.php");
    exit();
}
if (isset($_GET['action']) && $_GET['action'] == 'delete') 
 {
    $id = (int)$_GET['id'];
    mysqli_query($conn, "DELETE FROM rooms WHERE id = $id");
    header("Location: room-availability.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Elite - Room Availability & Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .page-header {
            background: linear-gradient(rgba(13, 27, 42, 0.8), rgba(13, 27, 42, 0.8)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 40px 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .page-header h2 { font-size: 2.5rem; margin-bottom: 10px; }
        .badge { 
		background: #f39c12;
		color: #fff; 
		padding: 6px 15px;
		border-radius: 20px; 
		font-weight: bold; 
		display: inline-block;
		}

        .content-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-open-modal {
            padding: 10px 20px;
            background: #f39c12;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
        }
        .btn-open-modal:hover {
			background: #d35400;
			}

        table { 
		width: 100%;
		border-collapse: collapse;
		margin-top: 10px;
		text-align: left; 
		}
        th, td { 
		padding: 15px;
		border-bottom: 1px solid #ddd;
		}
        th {
			background-color: #0d1b2a; 
			color: white; 
			text-transform: uppercase; 
			font-size: 0.9rem; 
			}
        tr:hover { 
		background-color: #f9f9f9;
		}

        /* Status Badges */
        .status-available { 
		background-color: #27ae60;
		color: white; 
		padding: 6px 12px;
		border-radius: 20px;
		font-size: 0.85rem;
		font-weight: bold; 
		}
        .status-booked {
			background-color: #e74c3c; 
			color: white;
			padding: 6px 12px;
			border-radius: 20px;
			font-size: 0.85rem;
			font-weight: bold;
			}

        .btn-action {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            font-size: 0.85rem;
            font-weight: bold;
            display: inline-block;
            margin-right: 5px;
            border: none;
            cursor: pointer;
        }
        .btn-edit { 
		background-color: #ffc107; 
		color: #000; 
		}
        .btn-delete {
			background-color: #e74c3c; 
			}

        /* Modal CSS */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 8px;
            width: 400px;
            text-align: left;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .form-group { 
		margin-bottom: 12px; 
		}
        .form-group label {
			display: block;

			margin-bottom: 4px; 
			font-weight: bold;
			color: #333;
			font-size: 0.9rem; 
			}
        .form-group input, .form-group select { 
		width: 100%;
		padding: 8px;
		border: 1px solid #ccc;
		border-radius: 4px;
		box-sizing: border-box; 
		}
        .close-btn {
			float: right;
			font-size: 22px;
			cursor: pointer; 
			color: #aaa; 
			}
    </style>
</head>
<body>

    <header>
        <div class="logo">Hotel Elite</div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="rooms.php">Rooms</a></li>
                <li><a href="room-availability.php" style="color: #f39c12;">Availability</a></li>
                <li><a href="food.php">Dining</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="index.php" style="color: #e74c3c;">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container"
	style="width: 90%;
	margin: 20px auto;">
        
        <div class="page-header">
            <span class="badge">Live Tracking & Management</span>
            <h2>Room Availability Status</h2>
            <p style="margin-top: 10px; 
			color: #ddd;">Manage rooms and live occupancy easily.</p>
        </div>

        <div class="content-box">
            <div class="action-bar">
                <h3 style="color: #0d1b2a;
				border-bottom: 2px solid #f39c12;
				display: inline-block;
				padding-bottom: 5px;
				margin: 0;">All Rooms Status</h3>
                <button class="btn-open-modal" onclick="openAddModal()">+ Add New Room</button>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Room No</th>
                        <th>Floor</th>
                        <th>Room Type</th>
                        <th>Status</th>
                        <th>Booking Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT r.*, 
                              CASE WHEN rb.id IS NOT NULL THEN 'Booked' ELSE 'Available' END as booking_status
                              FROM rooms r 
                              LEFT JOIN room_bookings rb ON r.room_no = rb.room_no AND rb.checkout_date >= CURDATE()
                              ORDER BY r.id DESC";
                    
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) 
						 {
                            $badgeClass = ($row['booking_status'] == 'Available') ? 'status-available' : 'status-booked';
                            $rStatus = isset($row['room_status']) ? $row['room_status'] : 'Active';
                            $floor = isset($row['floor']) ? $row['floor'] : '';

                            echo "<tr>";
                            echo "<td><strong>" . $row['room_no'] . "</strong></td>";
                            echo "<td>" . $floor . "</td>";
                            echo "<td>" . $row['room_type'] . "</td>";
                            echo "<td>" . $rStatus . "</td>";
                            echo "<td><span class='" . $badgeClass . "'>" . $row['booking_status'] . "</span></td>";
                            echo "<td>
                                    <button class='btn-action btn-edit' onclick='openEditModal(" . $row['id'] . ", \"" . $row['room_no'] . "\", \"" . $floor . "\", \"" . $row['room_type'] . "\", \"" . $rStatus . "\")'>Edit</button>
                                    <a href='room-availability.php?action=delete&id=" . $row['id'] . "' class='btn-action btn-delete' onclick='return confirm(\"Are you sure you want to delete this room?\")'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } 
					 else 
					 {
                        echo "<tr><td colspan='6' style='text-align:center;'>No rooms found in database.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <div id="roomModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle" style="margin-bottom: 15px; color: #0d1b2a;">Add New Room</h3>
            <form method="POST" id="roomForm">
                <input type="hidden" name="room_id" id="room_id">

                <div class="form-group">
                    <label>Room Number</label>
                    <input type="text" name="room_no" id="room_no" required placeholder="e.g., 101">
                </div>
                <div class="form-group">
                    <label>Floor</label>
                    <input type="text" name="floor" id="floor" required placeholder="e.g., 1st Floor">
                </div>
                <div class="form-group">
                    <label>Room Type</label>
                    <select name="room_type" id="room_type" required>
                        <option value="Standard Room">Standard Room</option>
                        <option value="Deluxe Room">Deluxe Room</option>
                        <option value="Executive Suite">Executive Suite</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Room Status</label>
                    <select name="room_status" id="room_status" required>
                        <option value="Active">Active</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <button type="submit" name="add_room" id="submitBtn" class="btn-open-modal" style="width: 100%; margin-top: 5px;">Save Room</button>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('modalTitle').innerText = "Add New Room";
            document.getElementById('roomForm').reset();
            document.getElementById('room_id').value = "";
            document.getElementById('submitBtn').name = "add_room";
            document.getElementById('submitBtn').innerText = "Save Room";
            document.getElementById('roomModal').style.display = 'flex';
        }

        function openEditModal(id, roomNo, floor, roomType, roomStatus) {
            document.getElementById('modalTitle').innerText = "Edit Room Details";
            document.getElementById('room_id').value = id;
            document.getElementById('room_no').value = roomNo;
            document.getElementById('floor').value = floor;
            document.getElementById('room_type').value = roomType;
            document.getElementById('room_status').value = roomStatus;
            
            document.getElementById('submitBtn').name = "update_room";
            document.getElementById('submitBtn').innerText = "Update Room";
            document.getElementById('roomModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('roomModal').style.display = 'none';
        }
    </script>

    <footer>
        <p style="text-align:center; padding: 20px; color: #666;">&copy; 2026 Hotel Elite. All Rights Reserved.</p>
    </footer>

</body>
</html>