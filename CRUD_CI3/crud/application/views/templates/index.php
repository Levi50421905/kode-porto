
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Blank Page</h1>

                    <table class="table table-bordered text-center">
                        <tr>
                            <td>No</td>
                            <td>Nama</td>
                            <td>NPM</td>
                            <td>Alamat</td>
                        </tr>
                        <?php 
                        $no = 1; 
                        foreach ($mahasiswa as $mhs) : ?>
                            <tr>
                                <td><?php echo $no++;?></td>
                                <td><?php echo $mhs['nama']; ?></td>
                                <td><?php echo $mhs['npm']; ?></td>
                                <td><?php echo $mhs['alamat']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->           