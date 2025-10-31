@props(['url' => null])
<tr>
	<td class="header">
		@if (trim($slot) === 'Intranet')
			<span style="display: inline-block; cursor: default;">
				<img src="https://i.ibb.co/XZb77Bb1/Logo-MDLM-vertical.png"  alt="Logo Municipalidad de La Molina" style="height: 100px; width:auto; max-width:200px;">
			</span>
		@else
			{!! $slot !!}
		@endif
	</td>
</tr>
