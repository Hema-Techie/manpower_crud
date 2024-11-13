<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manpower Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <style>
        body { background-color: #f8f9fa; }
        .form-title { color: #007bff; font-weight: bold; text-transform: uppercase; }
        .form-container { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); margin-top: 20px; }
        .mandatory:after { content: '*'; color: red; }
        .table-container { margin-top: 20px; }
        .btn-custom { margin-top: 10px; width: 100%; }
        .modal-header { background-color: #007bff; color: #fff; }
    </style>
</head>
<body>

<div class="container mt-5">
    <p class="text-center form-title h3">Manpower Form</p>
    <div class="form-container">
        <form id="myform">
            <input type="hidden" id="id" name="id">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <label for="name" class="form-label mandatory">Name</label>
                    <input type="text" id="name" name="name" class="form-control text_only" required>
                </div>
                <div class="col-sm-6">
                    <label for="date_of_birth" class="form-label mandatory">Date Of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-6">
                    <label for="skill_code" class="form-label mandatory">Skillset</label>
                    <select id="skill_code" name="skill_code" class="form-control" required>
                        <option value="">Select Skillset</option>
                        <?php
                        require_once 'db.php';
                        $query = "SELECT sid, skillset FROM mst_skillsets ORDER BY skillset";
                        $result = pg_query($dbconn, $query);
                        while ($row = pg_fetch_assoc($result)) {
                            echo "<option value=\"{$row['sid']}\">{$row['skillset']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" name="address" class="form-control"></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-6">
                    <label for="mobileno" class="form-label mandatory">Mobile Number</label>
                    <input type="text" id="mobileno" name="mobileno" class="form-control" required pattern="^[5-9][0-9]{9}$" title="Please enter a valid mobile number">
                </div>
                <div class="col-sm-6">
                    <label for="email" class="form-label mandatory">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea id="remarks" name="remarks" class="form-control"></textarea>
                </div>
            </div>
            <button type="submit" id="btnsubmit" class="btn btn-primary btn-custom">Save</button>
        </form>
    </div>

    <div class="table-container">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Date Of Birth</th>
                    <th>Skillset</th>
                    <th>Address</th>
                    <th>Mobile Number</th>
                    <th>Email</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tbody">
                <!-- Data will be dynamically populated here -->
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function () {
    fetch();

    $('#myform').on('submit', function (e) {
        e.preventDefault();

        let id = $('#id').val();
        let name = $('#name').val();
        let date_of_birth = $('#date_of_birth').val();
        let skill_code = $('#skill_code').val();
        let address = $('#address').val();
        let mobileno = $('#mobileno').val();
        let email = $('#email').val();
        let remarks = $('#remarks').val();

        let formData = {
            manid: id,
            name: name,
            date_of_birth: date_of_birth,
            skill_code: skill_code,
            address: address,
            mobileno: mobileno,
            email: email,
            remarks: remarks
        };

        let url = id ? 'update.php' : 'insert.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                alert(response);
                fetch();
                $('#myform')[0].reset();
                $('#id').val('');
                $('#btnsubmit').text('Save');
            },
            error: function (xhr, status, error) {
                let err = JSON.parse(xhr.responseText);
                alert(err.message);
            }
        });
    });
});

function fetch() {
    $.ajax({
        url: 'fetch.php',
        type: 'GET',
        success: function (data) {
            let rows = '';
            JSON.parse(data).forEach(function (row) {
                rows += `<tr>
                    <td>${row.name}</td>
                    <td>${row.date_of_birth}</td>
                    <td>${row.skillset}</td>
                    <td>${row.address}</td>
                    <td>${row.mobileno}</td>
                    <td>${row.email}</td>
                    <td>${row.remarks}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="edit(${row.manid})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord(${row.manid})">Delete</button>
                    </td>
                </tr>`;
            });
            $('#tbody').html(rows);
        }
    });
}

function edit(id) {
    $.ajax({
        url: 'edit.php',
        type: 'GET',
        data: { id: id },
        success: function (data) {
            let row = JSON.parse(data);
            if (row.error) {
                alert(row.error);
            } else {
                $('#id').val(row.manid);
                $('#name').val(row.name);
                $('#date_of_birth').val(row.date_of_birth);
                $('#skill_code').val(row.skill_code);
                $('#address').val(row.address);
                $('#mobileno').val(row.mobileno);
                $('#email').val(row.email);
                $('#remarks').val(row.remarks);
                $('#btnsubmit').text('Update');
            }
        }
    });
}

function deleteRecord(id) {
    if (confirm('Are you sure you want to delete this record?')) {
        $.ajax({
            url: 'delete.php',
            type: 'GET',
            data: { id: id },
            success: function (response) {
                alert(response);
                fetch();
            }
        });
    }
}
</script>
</body>
</html>
