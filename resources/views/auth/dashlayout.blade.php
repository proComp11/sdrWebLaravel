<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>SDR MANAGER WEB APP</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <nav class="navbar navbar-light navbar-expand-lg mb-5" style="background-color: #e3f2fd;">
        <div class="container">
            <img
                src="{{ URL::asset('assets/sdrlogo.png')}}"
                height="50px;"
                alt="SDR Logo"
                loading="lazy"
                style="margin-top: -1px;"
            />
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span style=" text-transform: uppercase;"> Welcome {{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item text-center" href="{{ route('signout') }}">Logout <i class="fa fa-sign-out"></i></a></li>
                    </ul>
                </li>
            </ul>
        </div>
        </div>
        </nav>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-2">
                <a href="dashboard" style="text-decoration:none; color:white">
                    <div class="card text-black mb-2 bg-info">
                        <div class="card-body text-center text-white">
                            <i style="color: green;" class="fas fa-table fa-1x"></i>
                            <h6>Work List</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-2">
                <a href="uploadmodule" style="text-decoration:none; color:white">
                    <div class="card text-black mb-2 bg-warning">
                        <div class="card-body text-center text-white">
                            <i style="color: blue;" class="fas fa-upload fa-1x"></i>
                            <h6>SDR Upload</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-2">
                <a href="headerlist" style="text-decoration:none; color:white">
                    <div class="card text-black mb-2 bg-secondary">
                        <div class="card-body text-center text-white">
                                <i style="color:blueviolet;" class="fas fa-columns fa-1x"></i>
                                <h6>Header List</h6>
                        </div>
                  </div>
                </a>
            </div>
            <div class="col-sm-2">
                <a href="headermatch" style="text-decoration:none; color:white">
                    <div class="card text-black mb-2 bg-danger">
                        <div class="card-body text-center text-white">
                            <i style="color:aqua;" class="fas fa-equals fa-1x"></i>
                            <p class="card-text">Header Match</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @yield('content')
    </body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.5/plupload.full.min.js" integrity="sha512-yLlgKhLJjLhTYMuClLJ8GGEzwSCn/uwigfXug5Wf2uU5UdOtA8WRSMJHJcZ+mHgHmNY+lDc/Sfp86IT9hve0Rg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var alias_names = [];
    var updFileName = '';
    window.addEventListener("load", function() {
        var path = "plupload/js/`";
        var uploader = new plupload.Uploader({
            browse_button: 'pickfiles',
            container: document.getElementById('file-input'),
            url: '{{ route("upload-file")}}',
            chunk_size: '1mb',
            max_retries: 2,
            filters: {
                max_file_size: '400gb',
                mime_types: [{
                    title: "Image",
                    extensions: "csv,txt"
                }]
            },
            multipart_params : {
                _token: CSRF_TOKEN
            },
            init: {
                PostInit: function() {
                    document.getElementById('file-name').innerHTML = '';
                },
                FilesAdded: function(up, files) {
                    plupload.each(files, function(file) {
                        document.getElementById('file-name').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                    });
                    uploader.start();
                },
                UploadProgress: function(up, file) {
                    $('#file-progress').removeClass('d-none');
                    $('#progressBar').css('width', file.percent + '%');
                    $('#progressBar').html(file.percent + '%');
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                },
                FileUploaded: function(up, file, result) {
                    setTimeout(function() {
                        $('#file-progress').addClass('d-none');
                    }, 2500);

                    //alert(result.response);
                    data = JSON.parse(result.response);
                    if (data.ok == 1) {
                        updFileName = data.filename;
                        $('#updldBtn').attr('disabled', false);
                    } else {
                        alert('Failed');
                    }
                },
                UploadComplete: function(up, file) {
                    Toastify({
                        text: 'Upload Succefully',
                        duration: 3000,
                        destination: "#",
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                        onClick: function(){} // Callback after click
                    }).showToast();
                    console.log("Upload Succefully.");
                },
                Error: function(up, err) {
                    console.log(err);
                }
            }
        });
        uploader.init();
    });

    function getData(id){
        $.ajax({
            type:"POST",
            url:"getHeaders",
            data: {_token: CSRF_TOKEN, headerid: id},
            success:function(data){
                var alias = JSON.parse(data.alias);
                var al = '';
                if(alias.length > 0){
                    $('#alias').html('');
                    for(var i = 0; i < alias.length; i++){
                        alias_names[i] = alias[i];
                        al += '&nbsp;&nbsp;<button type="button" class="btn btn-primary btn-sm position-relative mt-3">'+ alias[i] + '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">+</span></button>&nbsp;&nbsp;';
                    }
                    $('#alias').append(al);
                }else{
                    $('#alias').html('<span class="badge bg-secondary">NO ALIAS NAME ASSIGN</span>');
                }

                $('#header_list').html('EDIT HEADER LIST FOR : <span class="badge bg-info">' + data.name + '</span>');
                $('#id').val(data.id);
            },
            error:function(err){
                alert("ERROR :  " + eval(error));
            }
        })
    }
    function editRecords(){
        var alias = $('#aliasname').val();
        var id = $('#id').val();
        $.ajax({
            type:"POST",
            url:"editHeaders",
            data:{
                _token: CSRF_TOKEN,
                alias:alias,
                id:id
            },
            success:function(data){

                if(data.code == '1'){
                    Toastify({
                        text: data.status,
                        duration: 3000,
                        destination: "#",
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                        onClick: function(){} // Callback after click
                    }).showToast();
                    document.getElementById('aliasname').value = '';
                }else{
                    Toastify({
                        text: data.status,
                        duration: 3000,
                        destination: "#",
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                        onClick: function(){} // Callback after click
                    }).showToast();
                    $('#showHeaders').modal('hide');
                }
            },
            error:function(err){
                alert("error ", +err);
            }
        });
    }

    function refreshPage(){
        window.location.reload();
    }
    $(function() {
        $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
    });

    // save sdr upload information
    $('#updldBtn').click(function() {
            //alert(updFileName);
            var circle = $('#circle').val();
            var provider = $('#provider').val();
            var connection = $('#connection').val();
            var adf = $('#adf').val();
            var dobf = $('#dobf').val();
            var dbtable = $('#dbtable').val();
            var delimi = $('#delimi').val();
            var encls = $('#enclose').val();
            var mdate = $('#datepicker').val();
            var fileName = updFileName;

            $.ajax({
                type: "POST",
                url: "{{ route('save-sdrinfo')}}",
                data: {
                    _token: CSRF_TOKEN,
                    circle: circle,
                    provider: provider,
                    connection: connection,
                    adf: adf,
                    dobf: dobf,
                    dbtable: dbtable,
                    delimi: delimi,
                    enclosue: encls,
                    fileName: fileName,
                    monthDate: mdate
                },
                success: function(data) {
                    //alert(data);
                    if (data.status == 1) {
                        Toastify({
                            text: 'Data Successfully Added',
                            duration: 3000,
                            destination: "#",
                            newWindow: true,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: "center", // `left`, `center` or `right`
                            stopOnFocus: true, // Prevents dismissing of toast on hover
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)",
                            },
                            onClick: function(){} // Callback after click
                        }).showToast();
                    } else {
                        Toastify({
                            text: 'Data Failed To Insert',
                            duration: 3000,
                            destination: "#",
                            newWindow: true,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: "center", // `left`, `center` or `right`
                            stopOnFocus: true, // Prevents dismissing of toast on hover
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)",
                            },
                            onClick: function(){} // Callback after click
                        }).showToast();
                    }
                },
                error: function(error) {
                    alert("ERROR :  " + eval(error));
                }
            });
    });

    // fetch record by id
    function showDataById(id){
        document.getElementById("did").value = id;
        $.ajax({
            type:"POST",
            url:"{{ route('update-info')}}",
            data:{
                _token:CSRF_TOKEN,
                id:id
            },
            success:function(data){
                if(data.code == '1'){
                    $('#circle').val(data.result[0].circle.toLowerCase());
                    $('#provider').val(data.result[0].provider);
                    $('#connection').val(data.result[0].connection);
                    $('#adf').val(data.result[0].activation_date_format);
                    $('#dobf').val(data.result[0].dob_date_format);
                    $('#dbtable').val(data.result[0].db_table);
                    $('#delimi').val(data.result[0].delimiter);
                    $('#enclose').val(data.result[0].enclosure);
                    $('#datepicker').val(data.result[0].month_year);
            }else{

                Toastify({
                        text:'Failed To Fetch Data!!! Please contact Pralay mondal(CA ANALOGOUS TO SSP)',
                        duration: 3000,
                        destination: "#",
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                        onClick: function(){} // Callback after click
                    }).showToast();
              }
            },
            error:function(err){
                console.log(err);
            }
        });
    }

    // function for update data
    $('#updateBtn').on('click', ()=>{
        let id = $('#did').val();
        let circle = $('#circle').val();
        let provider = $('#provider').val();
        let connection = $('#connection').val();
        let adf = $('#adf').val();
        let dobf = $('#dobf').val();
        let dbtable = $('#dbtable').val();
        let delimi = $('#delimi').val();
        let encls = $('#enclose').val();
        let mdy = $('#datepicker').val();
        $.ajax({
            type:"POST",
            url:"{{ route('update-final')}}",
            data:{
                _token:CSRF_TOKEN,
                id:id,
                circle:circle,
                provider:provider,
                connection:connection,
                adf:adf,
                dobf:dobf,
                dbtable:dbtable,
                delimi:delimi,
                encls:encls,
                dateMy: mdy
            },
            success:function(data){
                if(data.code == '1'){
                    Toastify({
                        text:'Data Updated Successfully',
                        duration: 3000,
                        destination: "#",
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                        onClick: function(){} // Callback after click
                    }).showToast();
                }else{
                    Toastify({
                        text:'Failed To Fetch Data!!! Please contact Pralay mondal(CA ANALOGOUS TO SSP)',
                        duration: 3000,
                        destination: "#",
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                        onClick: function(){} // Callback after click
                    }).showToast();
                }
            },
            error:function(err){
                console.log(err);
            }
        });
    });


    // function for complete sdr job
    function completeJob(id){
        //alert(id);
        $.ajax({
            type:"POST",
            url:"{{ route('complete-process')}}",
            data:{
                _token:CSRF_TOKEN,
                id:id
            },
            success:function(data){
                if(data.code == '1'){
                    Toastify({
                        text:'Process Completed!!!',
                        duration: 3000,
                        destination: "#",
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                        onClick: function(){} // Callback after click
                    }).showToast();
                }else{
                    Toastify({
                        text:'Failed To Complete!!! Contact Pralay Mondal(CA analogous to SSP)',
                        duration: 3000,
                        destination: "#",
                        newWindow: true,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                        onClick: function(){} // Callback after click
                    }).showToast();
                }
            },
            error:function(err){
                console.log(err);
            }
        });
    }
</script>

