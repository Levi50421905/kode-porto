<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .img-thumbnail {
            height: 150px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1><?= $title ?></h1>
        
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <div class="mb-3">
            <a href="<?= site_url('image/add') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Gambar
            </a>
        </div>
        
        <?php if (empty($images)) : ?>
            <div class="alert alert-info">Belum ada data gambar.</div>
        <?php else : ?>
            <div class="row">
                <?php foreach ($images as $image) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?= base_url($image->image_path) ?>" class="card-img-top" alt="<?= $image->title ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $image->title ?></h5>
                                <p class="card-text"><?= $image->description ?></p>
                                <div class="d-flex justify-content-between">
                                    <a href="<?= site_url('image/edit/'.$image->id) ?>" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="<?= site_url('image/delete/'.$image->id) ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                            <div class="card-footer text-muted">
                                <small>Dibuat: <?= date('d-m-Y H:i', strtotime($image->created_at)) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>