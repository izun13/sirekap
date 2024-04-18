<!DOCTYPE html>
<html>
<head>
 <title>Selectize</title>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css" rel="stylesheet" />
 <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
</head>
<body>
<form method="POST">
 <select id="provinsi" name="provinsi">
  <option ></option>
  <option value="ACEH">ACEH</option>
  <option value="RIAU">RIAU</option>
  <option value="JAMBI">JAMBI</option>
  <option value="SUMATERA UTARA">SUMATERA UTARA</option>
  <option value="BENGKULU">BENGKULU</option>
  <option value="LAMPUNG">LAMPUNG</option>
  <option value="DKI JAKARTA">DKI JAKARTA</option>
  <option value="JAWA BARAT">JAWA BARAT</option>
  <option value="JAWA TENGAH">JAWA TENGAH</option>
  <option value="JAWA TIMUR">JAWA TIMUR</option>
 </select>
</form>
<script type="text/javascript">
        $('#provinsi').selectize({
            create: true,
            sortField: 'text'
        });
</script>
</body>
</html>