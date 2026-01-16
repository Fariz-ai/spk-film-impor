<?php
date_default_timezone_set("Asia/Jakarta");
require_once "config.php";

session_start();
$namaPengguna = $_SESSION['nama_lengkap'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SPK Film Impor</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/datatables.min.css">
    <link rel="stylesheet" href="assets/css/all.css">
    <link rel="stylesheet" href="assets/css/bootstrap-chosen.css">

    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: #ffffff;
            z-index: 1040;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: #6c757d;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
            color: #007bff;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: #ffffff;
        }

        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            min-height: calc(100vh - 70px);
        }

        .top-navbar {
            margin-left: 260px;
            height: 70px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content,
            .top-navbar {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1035;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>

<body class="bg-light">
    <!-- Cek status login -->
    <?php
    if ($_SESSION['status'] != "login") {
        header("Location:login.php");
    }
    ?>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <!-- Sidebar -->
    <div class="sidebar overflow-auto" id="sidebar">
        <!-- Logo -->
        <div class="text-center py-4 border-bottom">
            <img src="assets/images/logo.png" alt="PRIMA Cinema Multimedia" class="img-fluid px-3 mb-2" style="max-width: 200px;">
            <small class="text-muted d-block">Sistem Pendukung Keputusan</small>
        </div>

        <!-- Menu -->
        <ul class="nav flex-column py-3">
            <li class="nav-item">
                <a class="nav-link px-4 py-3" href="index.php">
                    <i class="fas fa-home mr-3"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-3" href="?page=alternatif">
                    <i class="fas fa-film mr-3"></i> Data Alternatif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-3" href="?page=kriteria">
                    <i class="fas fa-list mr-3"></i> Data Kriteria
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-3" href="?page=subkriteria">
                    <i class="fas fa-layer-group mr-3"></i> Data Sub-Kriteria
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-3" href="?page=penilaian">
                    <i class="fas fa-calculator mr-3"></i> Penilaian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-3" href="?page=hasil">
                    <i class="fas fa-chart-line mr-3"></i> Hasil Perankingan
                </a>
            </li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') : ?>
                <li class="nav-item">
                    <a class="nav-link px-4 py-3" href="?page=pengguna">
                        <i class="fas fa-users mr-3"></i> Data Pengguna
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top top-navbar">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <!-- Toggle Button untuk Mobile -->
                <button class="btn btn-link text-white d-lg-none mr-2" id="sidebarToggle" type="button">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <!-- Page Title -->
                <h4 class="mb-0 font-weight-bold text-white">
                    <?php
                    $page = $_GET['page'] ?? "";

                    $titles = [
                        "" => "Dashboard",
                        "alternatif" => "Data Alternatif",
                        "kriteria" => "Data Kriteria",
                        "subkriteria" => "Data Sub-Kriteria",
                        "penilaian" => "Penilaian",
                        "hasil" => "Hasil Perankingan",
                        "pengguna" => "Data Pengguna",
                    ];

                    if (array_key_exists($page, $titles)) {
                        echo $titles[$page];
                    } else {
                        echo "Halaman Tidak Ditemukan";
                    }
                    ?>
                </h4>
            </div>

            <!-- Right Side -->
            <div class="d-flex align-items-center">
                <!-- User Info -->
                <div class="bg-white rounded-pill px-3 py-2 mr-3 d-none d-md-inline-block">
                    <i class="fas fa-user-circle text-primary"></i>
                    <span class="ml-2 font-weight-medium text-dark"><?php echo $namaPengguna; ?></span>
                </div>

                <!-- Logout Button -->
                <a href="logout.php" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-md-inline ml-1">Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content p-4">
        <div class="container-fluid">
            <?php
            // Pengaturan menu
            $page = isset($_GET['page']) ? $_GET['page'] : "";
            $action = isset($_GET['action']) ? $_GET['action'] : "";

            // Routing
            if ($page == "") {
                include "dashboard.php";
            } elseif ($page == "alternatif") {
                if ($action == "") {
                    include "alternatif/index.php";
                } elseif ($action == "tambah") {
                    include "alternatif/tambah.php";
                } elseif ($action == "edit") {
                    include "alternatif/edit.php";
                } elseif ($action == "hapus") {
                    include "alternatif/hapus.php";
                } elseif ($action == "cetak") {
                    include "alternatif/cetak.php";
                }
            } elseif ($page == "kriteria") {
                if ($action == "") {
                    include "kriteria/index.php";
                } elseif ($action == "tambah") {
                    include "kriteria/tambah.php";
                } elseif ($action == "edit") {
                    include "kriteria/edit.php";
                } elseif ($action == "hapus") {
                    include "kriteria/hapus.php";
                } elseif ($action == "cetak") {
                    include "kriteria/cetak.php";
                }
            } elseif ($page == "subkriteria") {
                if ($action == "") {
                    include "subkriteria/index.php";
                } elseif ($action == "tambah") {
                    include "subkriteria/tambah.php";
                } elseif ($action == "edit") {
                    include "subkriteria/edit.php";
                } elseif ($action == "hapus") {
                    include "subkriteria/hapus.php";
                } elseif ($action == "cetak") {
                    include "subkriteria/cetak.php";
                }
            } elseif ($page == "penilaian") {
                if ($action == "") {
                    include "penilaian/index.php";
                } elseif ($action == "tambah") {
                    include "penilaian/tambah.php";
                } elseif ($action == "edit") {
                    include "penilaian/edit.php";
                } elseif ($action == "hapus") {
                    include "penilaian/hapus.php";
                } elseif ($action == "cetak") {
                    include "penilaian/cetak.php";
                }
            } elseif ($page == "hasil") {
                if ($action == "") {
                    include "hasil/index.php";
                } elseif ($action == "tambah") {
                    include "hasil/cetak.php";
                }
            } elseif ($page == "pengguna") {
                if ($_SESSION['role'] !== 'Admin') {
                    include "aksesDitolak.php";
                } else {
                    if ($action == "") {
                        include "pengguna/index.php";
                    } elseif ($action == "tambah") {
                        include "pengguna/tambah.php";
                    } elseif ($action == "edit") {
                        include "pengguna/edit.php";
                    } elseif ($action == "hapus") {
                        include "pengguna/hapus.php";
                    } elseif ($action == "cetak") {
                        include "pengguna/cetak.php";
                    }
                }
            } elseif ($page == "logout") {
                include "logout.php";
            } else {
                include "notFound.php";
            }
            ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery-3.7.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/all.js"></script>
    <script src="assets/js/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            // DataTables
            $('#table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "responsive": true
            });

            // Active menu
            var currentPage = "<?php echo $page; ?>";
            $('.sidebar .nav-link').removeClass('active');

            if (currentPage === "") {
                $('.sidebar .nav-link[href="index.php"]').addClass('active');
            } else {
                $('.sidebar .nav-link[href="?page=' + currentPage + '"]').addClass('active');
            }

            // Sidebar toggle
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('show');
                $('#sidebarOverlay').toggleClass('show');
            });

            $('#sidebarOverlay').on('click', function() {
                $('#sidebar').removeClass('show');
                $('#sidebarOverlay').removeClass('show');
            });

            $('.sidebar .nav-link').on('click', function() {
                if ($(window).width() <= 991) {
                    $('#sidebar').removeClass('show');
                    $('#sidebarOverlay').removeClass('show');
                }
            });
        });
    </script>

    <script src="assets/js/chosen.jquery.min.js"></script>
    <script>
        $(function() {
            $('.chosen').chosen();
        });
    </script>

    <script>
        $(document).ready(function() {
            // Script untuk load sub-kriteria berdasarkan kriteria (halaman penilaian TAMBAH)
            $('#kriteria_id').on('change', function() {
                var kriteriaId = $(this).val();
                console.log('Kriteria ID:', kriteriaId);

                if (kriteriaId) {
                    $.ajax({
                        url: 'penilaian/get_subkriteria.php',
                        type: 'POST',
                        data: {
                            kriteria_id: kriteriaId
                        },
                        success: function(response) {
                            console.log('Response:', response);
                            $('#sub_kriteria_id').html(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('Gagal memuat data sub-kriteria');
                        }
                    });
                } else {
                    $('#sub_kriteria_id').html('<option value="">Pilih Kriteria terlebih dahulu</option>');
                }
            });

            // Script untuk load sub-kriteria berdasarkan kriteria (halaman penilaian EDIT)
            $('#kriteria_id_edit').on('change', function() {
                var kriteriaId = $(this).val();
                console.log('Kriteria ID Edit:', kriteriaId);

                if (kriteriaId) {
                    $.ajax({
                        url: 'penilaian/get_subkriteria.php',
                        type: 'POST',
                        data: {
                            kriteria_id: kriteriaId
                        },
                        success: function(response) {
                            console.log('Response Edit:', response);
                            $('#sub_kriteria_id_edit').html(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error Edit:', error);
                            alert('Gagal memuat data sub-kriteria');
                        }
                    });
                } else {
                    $('#sub_kriteria_id_edit').html('<option value="">Pilih Kriteria terlebih dahulu</option>');
                }
            });
        });
    </script>

</body>

</html>