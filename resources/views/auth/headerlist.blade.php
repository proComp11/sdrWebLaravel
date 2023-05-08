@extends('auth.dashlayout')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header text-center text-white bg-secondary">
                    HEADER LIST
                </div>
                <div class="card-body">
                    <table class="table table-warning table-striped align-middle table-sm text-center">
                        <thead>
                            <th>#</th>
                            <th>NAME</th>
                            <th>ALIAS</th>
                            <th>ACTION</th>
                        </thead>
                        <tbody>
                            @if(!empty($header) && $header->count())

                                @php
                                    $counter = 1
                                @endphp
                                @foreach ($header as $lst)
                                    <tr>
                                        <td>{{ $counter++; }}</td>
                                        <td><span class="badge rounded-pill bg-primary">{{ $lst->name }}</span></td>
                                        <td>
                                            @php
                                                $alias = json_decode($lst->alias);
                                            @endphp
                                            @for ($i = 0; $i < count($alias); $i++)
                                                <span class='badge bg-secondary'>{{ $alias[$i] }}</span> &nbsp;&nbsp;
                                            @endfor
                                        </td>
                                        <td><button class="btn-outline-info" data-bs-toggle="modal" data-bs-target="#showHeaders" onclick="getData({{$lst->id}})" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Show Header List"><i class="fa fa-edit" style="color:green;"></i></button></td>
                                    </tr>
                                @endforeach

                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal Section start for header list showing -->
<div class="modal" id="showHeaders">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title" id="header_list"></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <input type="hidden" name="id" id="id" />
                    <label>Alias</label>
                    <div id="alias"></div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Add Alias</label>
                        <input type="text" name="alias" id="aliasname" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="col mt-2">
                <div class="form-group">
                    <button class="btn btn-success btn-sm mr-2" onclick="editRecords()">Insert</button>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" onclick="refreshPage()">Close</button>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<!-- Edit Modal Section end-->
@endsection
