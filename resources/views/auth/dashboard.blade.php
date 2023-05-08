@extends('auth.dashlayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @if ($message = Session::get('success'))
                <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
                <script type="text/javascript">
                Toastify({
                        text: "Welcome to SDR MANAGER",
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
                </script>
            @endif
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table width="100%" class="table table-warning table-striped align-middle table-sm">
                        <thead class="table-info">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">CIRCLE</th>
                                <th scope="col">PROVIDER</th>
                                <th scope="col">CONNECTION</th>
                                <th scope="col">ACTIVATION DATE</th>
                                <th scope="col">DOB DATE</th>
                                <th scope="col">DB TABLE</th>
                                <th scope="col">DELIMETER</th>
                                <th scope="col">FILENAME</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">ERROR</th>
                                <th scope="col">UPLOAD DATE</th>
                                <th scope="col">UPLOAD BY</th>
                                <th scope="col">COMPLETE AT</th>
                                <th scope="col">NOTE</th>
                                <th scope="col">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($work) && $work->count())
                                @php($counter = 1)
                                @foreach ($work as $lst)
                                    <?php
                                        $date1 = date('Y-m-d', strtotime($lst->created_at));
                                        $date2 = date('Y-m-d');
                                        $startTimeStamp = strtotime($date1);
                                        $endTimeStamp = strtotime($date2);
                                        $timeDiff = abs($endTimeStamp - $startTimeStamp);
                                        $numberDays = $timeDiff/86400;
                                        $numberDays = intval($numberDays);
                                    ?>
                                    <tr>
                                        <td>{{ $counter++; }}</td>
                                        <td>{{ $lst->circle }}</td>
                                        <td style="word-wrap: break-word;min-width: 100px;max-width: 100px;white-space:normal;">{{ $lst->provider }}</td>
                                        <td>{{ $lst->connection }}</td>
                                        <td style="word-wrap: break-word;min-width: 100px;max-width: 100px;white-space:normal;">{{ $lst->activation_date_format }}</td>
                                        <td style="word-wrap: break-word;min-width: 100px;max-width: 100px;white-space:normal;">{{ $lst->dob_date_format }}</td>
                                        <td>{{ $lst->db_table }}</td>
                                        <td>{{ $lst->delimiter }}</td>
                                        <td style="word-wrap: break-word;min-width: 100px;max-width: 100px;white-space:normal;">
                                            @switch($lst->status)
                                                @case(3)
                                                    <?php
                                                        $file_url = public_path('processed/'.$lst->filepath);
                                                        echo '<a href="'.$file_url.'" target="_blank" download>'.$lst->filepath.'</a>';
                                                    ?>
                                                    @break
                                                @case(4)
                                                    <?php
                                                        $file_url = public_path('processed/'.$lst->filepath);
                                                        echo '<a href="'.$file_url.'" target="_blank" download>'.$lst->filepath.'</a>';
                                                    ?>
                                                    @break
                                                @default
                                                    <?php
                                                        echo $lst->filepath;
                                                    ?>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($lst->status)
                                                @case(1)
                                                    <span class="badge bg-secondary">Pending</span>
                                                    @break
                                                @case(2)
                                                    <span class="badge bg-info">OnProcess</span>
                                                    @break
                                                @case(3)
                                                    <span class="badge bg-success">Complete</span>
                                                    @break
                                                @case(4)
                                                    <span class="badge bg-warning">Complete With Error</span>
                                                    @break
                                                @case(5)
                                                    <span class="badge bg-danger">Error</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-dark">Cancel</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($lst->status)
                                                @case(4)
                                                    <?php
                                                        $file_url = public_path('error/'.$lst->filepath);
                                                        echo '<a href="'.$file_url.'" target="_blank" download>'.$lst->error.'</a>';
                                                    ?>
                                                    @break
                                                @default
                                                    <?php
                                                        echo $lst->error;
                                                    ?>
                                            @endswitch
                                            {{ $lst->error}}
                                        </td>
                                        <td>{{ date('d-m-Y h:s a', strtotime($lst->created_at));}}</td>
                                        <td>{{ Auth::user()->name }}</td>
                                        <td>{{ $lst->compete_at }}</td>
                                        <td></td>
                                        <td>
                                            @if($lst->status == 3 || $lst->status == 4 || $lst->status == 5 || $lst->status == 6)

                                                @if($numberDays < 7)
                                                    <i class="fa fa-pencil-square" style="font-size:20px; color:green;" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="showDataById({{$lst->id}})">
                                                    </i>
                                                @endif
                                            @elseif ($lst->status == 3 && $lst->status == 4 )
                                                <i class="fa fa-check-circle" style="font-size:20px; color:green;" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Complete Yourself" onclick="completeJob({{$lst->id}})"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr align="center">
                                    <td colspan="15">There are no data.</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{  $work->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for showing data by id -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Your Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="container" class="container-fluid mt-2">
                    <div class="row" id="wrkList" style="display:block;">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="card">
                                    <div class="col text-center" id="msg"></div>
                                    <div class="card-header text-center bg-success text-white">
                                        <h5>WORK LIST</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <label>CIRCLE</label>
                                                <!-- <input type="text" class="form-control" id="circle" placeholder="ENTER CIRCLE" required/> -->
                                                <select class="form-control text-uppercase" id="circle" required>
                                                    <option selected>SELECT CIRCLE</option>
                                                    <option value="andhra">andhra</option>
                                                    <option value="bhjhk">bhjhk</option>
                                                    <option value="delhi">delhi</option>
                                                    <option value="gujarat">gujarat</option>
                                                    <option value="haryana">haryana</option>
                                                    <option value="hp">hp</option>
                                                    <option value="jk">jk</option>
                                                    <option value="karnataka">karnataka</option>
                                                    <option value="kerala">kerala</option>
                                                    <option value="mah_goa">mah_goa</option>
                                                    <option value="mp">mp</option>
                                                    <option value="ne">ne</option>
                                                    <option value="odisa">odisa</option>
                                                    <option value="punjab">punjab</option>
                                                    <option value="rajasthan">rajasthan</option>
                                                    <option value="telangana">telangana</option>
                                                    <option value="tmnadu">tmnadu</option>
                                                    <option value="upe">upe</option>
                                                    <option value="upw">upw</option>
                                                    <option value="KOLKATA">KOLKATA</option>
                                                    <option value="WESTBENGAL">WESTBENGAL</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label>PROVIDER</label>
                                                <select class="form-control text-uppercase" id="provider" required>
                                                    <option selected>SELECT PROVIDER</option>
                                                    <option value="VODAFONE">VODAFONE</option>
                                                    <option value="AIRTEL">AIRTEL</option>
                                                    <option value="JIO">JIO</option>
                                                    <option value="BSNL">BSNL</option>
                                                    <option value="TATA">TATA</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <label>CONNECTION</label>
                                                <select class="form-control text-uppercase" id="connection" required>
                                                    <option selected>SELECT CONNECTION TYPE</option>
                                                    <option value="POSTPAID">POSTPAID</option>
                                                    <option value="PREPAID">PREPAID</option>
                                                    <option value="POSTPAID_FNAT">POSTPAID_FNAT</option>
                                                    <option value="PREPAID_FNAT">PREPAID_FNAT</option>
                                                    <option value="POSTPAID_LL">POSTPAID_LL</option>
                                                    <option value="PREPAID_LL">PREPAID_LL</option>
                                                    <option value="UNKNOWN">UNKNOWN</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label>ACTIVATION DATE FORMAT</label>
                                                <input type="text" class="form-control" id="adf" placeholder="ENTER ACTIVATION DATE" required />
                                                <input type="hidden" name="" id="did"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <label>DOB DATE FORMAT</label>
                                                <input type="text" class="form-control" id="dobf" placeholder="ENTER DOB DATE FORMAT" />
                                            </div>
                                            <div class="col">
                                                <label>DB TABLE</label>
                                                <!-- <input type="text" class="form-control" id="dbtable" placeholder="ENTER DATABASE TABLE NAME" required/> -->
                                                <select class="form-control text-lowercase" id="dbtable">
                                                    <option selected>SELECT DB TABLE</option>
                                                    <option value="andhra">andhra</option>
                                                    <option value="bhjhk">bhjhk</option>
                                                    <option value="delhi">delhi</option>
                                                    <option value="gujarat">gujarat</option>
                                                    <option value="haryana">haryana</option>
                                                    <option value="hp">hp</option>
                                                    <option value="jk">jk</option>
                                                    <option value="karnataka">karnataka</option>
                                                    <option value="kerala">kerala</option>
                                                    <option value="mah_goa">mah_goa</option>
                                                    <option value="mp">mp</option>
                                                    <option value="ne">ne</option>
                                                    <option value="odisa">odisa</option>
                                                    <option value="punjab">punjab</option>
                                                    <option value="rajasthan">rajasthan</option>
                                                    <option value="telangana">telangana</option>
                                                    <option value="tmnadu">tmnadu</option>
                                                    <option value="upe">upe</option>
                                                    <option value="upw">upw</option>
                                                    <option value="airtel">airtel</option>
                                                    <option value="bsnl">bsnl</option>
                                                    <option value="voda">voda</option>
                                                    <option value="jio">jio</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <label>FIELD SEPERATED BY</label>
                                                <input type="text" class="form-control" id="delimi" placeholder="ENTER DELIMITER" />
                                            </div>
                                            <div class="col">
                                                <label>FIELD ENCLOSED BY</label>
                                                <input type="text" class="form-control" id="enclose" placeholder="ENTER ENCLOSURE" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <label>DATE</label>
                                                <input type="text" class="form-control" id="datepicker" placeholder="ENTER MONTH AND YEAR"/>
                                            </div>
                                            <div class="col">
                                                <!--blank space-->
                                            </div>
                                        </div>
                                        <div class="row" id="err">

                                        </div>
                                        <div class="row" id="succ">

                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <button type="button" id="updateBtn" class="btn btn-primary btn-sm">Update</button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" onclick="refreshPage()">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
@stop

