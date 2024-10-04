<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pengajuan Cuti</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-group input[type="radio"] {
            margin-right: 15px;
        }

        input[type="radio"]:checked {
            accent-color: rgba(204, 2, 0, 1);
        }

        .form-group input[type="date"],
        .form-group textarea,
        .form-group input[type="file"],
        .form-group select {
            width: 100%;
            padding: 15px;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        .form-group small {
            display: block;
            margin-top: 5px;
            color: #6c757d;
            font-size: 12px;
        }

        .form-group .text-danger {
            color: red;
            font-size: 14px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .buttons button {
            width: 48%;
            padding: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .buttons .btn-primary {
            background-color: rgba(204, 2, 0, 1);
            color: white;
        }

        .buttons .btn-secondary {
            background-color: rgba(247, 183, 49, 1);
            color: white;
        }

        .status-table {
            margin-top: 40px;
            font-size: 16px;
        }

        .status-table h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .status-table table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        .status-table th, 
        .status-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .status-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .status-table td {
            background-color: #fff;
        }

        .status-table .edit {
            color: #007bff;
            text-decoration: none;
        }

        .status-table .edit:hover {
            text-decoration: underline;
        }

        .approved {
            color: #37BE47;
        }

        .in-progress {
            color: #007bff;
        }

        @media (max-width: 768px) {
            .container {
                max-width: 95%;
                padding: 20px;
            }

            .buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .buttons button {
                width: 100%;
                margin-bottom: 10px;
            }
        }

        /* Border merah untuk select element */
        select {
            border-color: rgba(204, 2, 0, 1);
        }
        

        /* Border merah untuk setiap form element */
        .border-red {
            border: 1px solid red;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Formulir Pengajuan Cuti</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
             
            <!-- Pilih Cuti -->
            <div class="form-group border-red">
                <label for="Pilih_cuti">Pilih Cuti <span class="text-danger">*</span></label>
                <select name="Pilih_cuti" id="Pilih_cuti" required>
                    <option value="">Please Select</option>
                    <option value="1">Izin</option>
                    <option value="2">Sakit</option>
                </select>
                @error('penanggung_jawab')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Periode Cuti -->
            <div class="form-group border-red">
                <label for="periode_mulai">Periode Cuti <span class="text-danger">*</span></label>
                <input type="date" name="periode_mulai" id="periode_mulai" value="{{ old('periode_mulai') }}">
                <input type="date" name="periode_selesai" id="periode_selesai" value="{{ old('periode_selesai') }}">
                @error('periode_mulai')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                @error('periode_selesai')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Alasan Cuti -->
            <div class="form-group border-red">
                <label for="alasan">Alasan Cuti <span class="text-danger">*</span></label>
                <textarea name="alasan" id="alasan" rows="3">{{ old('alasan') }}</textarea>
                @error('alasan')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Unggah Berkas -->
            <div class="form-group border-red">
                <label for="file">Unggah Berkas</label>
                <input type="file" name="file" id="file">
                <small>Unggah file PDF/JPG/PNG, max 2MB</small>
                @error('file')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="buttons">
                <button type="reset" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Ajukan</button>
            </div>
        </form>

        <div class="status-table">
            <h2>Status Pengajuan</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <th>Jenis Cuti</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>10/11/2024</td>
                        <td>Izin</td>
                        <td>10/11/2024 - 10/11/2024</td>
                        <td class="approved">Disetujui</td>
                        <td><a href="#" class="edit">Edit</a></td>
                    </tr>
                    <tr>
                        <td>12/11/2024</td>
                        <td>Sakit</td>
                        <td>12/11/2024 - 14/11/2024</td>
                        <td class="in-progress">Diproses</td>
                        <td><a href="#" class="edit">Edit</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
