<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1><?= $title ?></h1>
        
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif; ?>
        
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
        
        <?= form_open_multipart('image/add') ?>
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" class="form-control <?= form_error('title') ? 'is-invalid' : '' ?>" id="title" name="title" value="<?= set_value('title') ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea class="form-control <?= form_error('description') ? 'is-invalid' : '' ?>" id="description" name="description" rows="3"><?= set_value('description') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">Gambar</label>
                <input type="file" class="form-control-file <?= form_error('image') ? 'is-invalid' : '' ?>" id="image" name="image">
                <small class="form-text text-muted">Format yang diperbolehkan: gif, jpg, jpeg, png. Maksimal 2MB.</small>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= site_url('image') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        <?= form_close() ?>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>