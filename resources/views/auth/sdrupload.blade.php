@extends('auth.dashlayout')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header text-center text-white bg-warning">
                    SDR UPLOAD
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
                                <option value="SIKKIM">SIKKIM</option>
                                <option value="ANDAMAN">ANDAMAN</option>
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
                    <div id="file-input" class="mt-2">
                        <input type="file" class="form-control" id="pickfiles">
                    </div>
                    <div id="file-name"></div>
                    <div class="progress d-none" id="file-progress">
                        <div id="progressBar" class="progress-bar progress-bar-striped" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <button type="button" id="updldBtn" class="btn btn-primary btn-sm" disabled="disabled">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
