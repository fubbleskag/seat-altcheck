@extends('web::layouts.grids.4-8')

@section('title', 'Alt Checker')
@section('page_header', 'Alt Checker')

@section('left')

	<div class="card card-primary card-solid">
		<div class="card-header">
			<h3 class="card-title">Alts to Check</h3>
		</div>
		<div class="card-body">
			<p class="card-text">List of alts to check, one per line.</p>
			<textarea name="altlist" id="altlist" rows="15" style="width: 100%" onclick="this.focus();this.select()"></textarea>
			<label for="corporations">Corporation:</label>
			<select id="corporations" class="form-control">
				@foreach ($corps as $corp)
					@if ( $corp->corporation_id == $my_corporation_id )
						<option value="{{ $corp->corporation_id }}" selected>{{ $corp->name }}</option>
					@else
						<option value="{{ $corp->corporation_id }}">{{ $corp->name }}</option>
					@endif
				@endforeach
			</select>
		</div>
		<div class="card-footer">
			<button type="button" id="checkalts" class="btn btn-info btn-flat">
				<span class="fa fa-sync"></span>
				Check Alts
			</button>
		</div>
	</div>

@stop

@section('right')

	<div class="card card-primary card-solid" id="reportbox">
		<div class="card-body">
			<div class="table-responsive" style="overflow: auto">
				<table id="report" class="table table-condensed table-striped no-footer">
					<thead>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>

@stop

@push('javascript')
<script type="application/javascript">

	var button = $('#checkalts');
	var table;
	var report = $('#report');

	$( document ).ready(function(){
		$('#reportbox').hide();
	});

	button.on('click',function(){
		button.prop('disabled',true);
		button.html(
			`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
		);

		$('#reportbox').hide();
		button.removeClass("bg-danger");

		if (table) {
			table.clear;
			table.destroy;
			//report.find('thead, tbody').empty();
		}
		report.find('thead, tbody').empty();

		// do ajax here

		$.ajax({
			headers: function() {},
			url: "/altcheck/runReport/",
			type: "POST",
			data: {
				altlist: $('#altlist').val(),
				_token: "{{ csrf_token() }}",
			},
			datatype: 'json',
			timeout: 10000
		}).done( function( result ) {
			console.log( result );
			report.find("thead").append("<tr><th>&nbsp;</th><th>Character</th><th>Main</th>");
			body = '';
			$.each( result, function( character, details ) {
				console.log( details );
				if ( details.mainCorpId == 98377551 ) { // known and in-corp
					icon = '<span class="text-success"><i class="fas fa-check-circle" data-toggle="tooltip" title="" data-original-title=""></i></span>';
				} else if ( details.mainCorpId == undefined ) { // unknown
					icon = '<span class="text-warning"><i class="fas fa-question-circle" data-toggle="tooltip" title="" data-original-title=""></i></span>';
				} else {
					icon = '<span class="text-danger"><i class="fas fa-times-circle" data-toggle="tooltip" title="" data-original-title=""></i></span>';
				}
				if ( details.main == undefined ) {
					main = "N/A";
				} else {
					main = details.main;
				}
				body += '<tr><td>' + icon + '</td><td>' + character + '</td><td>' + main + '</td></tr>';
			} );

			// <i class="fas fa-times-circle" data-toggle="tooltip" title="" data-original-title=""></i>
			// <i class="fas fa-check-circle" data-toggle="tooltip" title="" data-original-title=""></i>

			report.find("tbody").prepend(body);
			$('#reportbox').show();
			button.html(
				`<span class="fa fa-sync"></span>
					Check Alts
				</button>`
			);
			button.prop("disabled", false);
		})
		.fail( function() {
			button.html(
				`<span class="fa fa-sync"></span>
					Try Again (Last Request Timed Out)
				</button>`
			);
			button.addClass("bg-danger")
			button.prop("disabled", false);  
		});
	});

</script>
@endpush
