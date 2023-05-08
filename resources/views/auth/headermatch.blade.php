@extends('auth.dashlayout')
@section('content')
<div class="container">
    <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header text-center text-white bg-danger">
                        HEADER MATCHING
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" id="checkfrom">
                            <div class="form-group">
                                <textarea class="form-control text-lowercase" name="fieldName" placeholder="Enter Fields Name" rows="5"></textarea>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-outline-success btn-sm fw-bold">Check</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    </div>
    <div class="row justify-content-center mt-2" style="display:none;" id="sHeaderDiv">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center text-white bg-success">
                    HEADERS NAMES
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Alias</th>
                        </thead>
                        <tbody id="headerData">
                            <!--dynamic data-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-2" style="display:none;" id="errorHeader">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center text-white bg-danger">
                    ERRORS
                </div>
                <div class="card-body" id="error">

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function() {
				const columns = JSON.parse(atob("<?php echo $header; ?>"));
				const columnArray = Object.keys(columns);
				let foundErorr = false;
				let errorMessage = '';
				document.getElementById("checkfrom").addEventListener("submit", (e) => {
					e.preventDefault();
					const row = document.getElementsByName("fieldName")[0].value.split("\n");
					if (row.length == 0) {
						alert('please submit header');
					}
					foundErorr = false;
					errorMessage = '';
					columnArray.forEach(column => {
						columns[column].index = null;
					});
					for (key in row) {
						const header = row[key].replace(/\s+/g, " ").trim().toLowerCase();
						for (let i = 0; i < columnArray.length; i++) {
							if (columns[columnArray[i]].alias.includes(header)) {
								if (columns[columnArray[i]].index === null) {
									columns[columnArray[i]].index = row[key];
								} else {
									errorMessage = `duplicate heading found for ${columnArray[i]} ${columns[columnArray[i]].index} and ${row[key]} found`;
									foundErorr = true;
								}
								break;
							}
						}
						if (foundErorr) {
							break;
						}
					}

					if (!foundErorr) {
						const allfound = columnArray.every(column => {
							if (columns[column].index !== null || columns[column].empty == true) {
								return true;
							} else {
								errorMessage = `column missing ${column}`;
								foundErorr = true;
								return false;
							}
						});
					}
					if (foundErorr) {
						//show errorMessage
						document.getElementById('errorHeader').style.display = "block";
						$('#sHeaderDiv').hide();
						document.getElementById('error').innerHTML = "<p class='alert alert-danger text-center text-black'>"+errorMessage+"</p>";
						console.log(errorMessage);

					} else {
						//show table
						console.log(columns);
						let i = 1;
						document.getElementById('errorHeader').style.display = "none";
						$('#sHeaderDiv').show();
						$('#headerData').innerHTML = "";
						columnArray.forEach(column=>{
							//import table structure
							$('#headerData').append('<tr><td>'+ i++ +'</td><td>'+ column +'</td><td>'+ columns[column].index +'</td></tr>');
						})
					}
				});
			})();
</script>

@endsection
