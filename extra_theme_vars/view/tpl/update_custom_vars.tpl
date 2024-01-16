<div class="generic-content-wrapper">
	<div class="section-title-wrapper">
		<h2>{{$title}}</h2>
	</div>
    <div class="section-content-wrapper">
        <div class="descriptive-text">{{$desc}}</div>
        {{if $variables}}
        <style>
            table#customVarsTable {
                border:1px solid #b3adad;
                border-collapse:collapse;
                padding:5px;
                margin-top:10px;
                width:100%;
            }
            table#customVarsTable th {
                border:1px solid #b3adad;
                text-align:center;
                padding:5px;
                background: #f0f0f0;
                color: #313030;
            }
            table#customVarsTable td {
                border:1px solid #b3adad;
                text-align:center;
                padding:5px;
                background: #ffffff;
                color: #313030;
            }
        </style>
        <table id="customVarsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Value</th>
                    <th>#</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
            {{foreach $variables as $variable}}
                <tr>
                    <td>{{$variable.name}}</td>
                    <td>{{$variable.value}}</td>
                    <td><a href="{{$urlRoot}}?action=edit&var={{$variable.name}}">Edit</a></td>
                    <td><a href="{{$urlRoot}}?action=delete&var={{$variable.name}}" onclick="if (!confirm('Are you sure?')) return false;">Delete</a></td>
                </tr>
            {{/foreach}}    
            </tbody>
        </table>
        {{/if}}
    </div>
</div>