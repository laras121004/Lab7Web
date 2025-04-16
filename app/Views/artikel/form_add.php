<?= $this->include('template/admin_header'); ?>

<h2><?= $title; ?></h2>

<form action="" method="post" style="width: 100%; max-width: 800px;">
    <p>
        <input type="text" name="judul" placeholder="Judul Artikel" style="width: 100%; padding: 10px; font-size: 16px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px;">
    </p>
    <p>
        <textarea name="isi" cols="50" rows="10" placeholder="Isi Artikel" style="width: 100%; padding: 10px; font-size: 16px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
    </p>
    <p>
        <input type="submit" value="Kirim" class="btn btn-large" style="width: 10%; padding: 10px; font-size: 16px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
    </p>
</form>

<?= $this->include('template/admin_footer'); ?>