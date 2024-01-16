<div class="generic-content-wrapper">
	<div class="section-title-wrapper">
		<h2>{{$title}}</h2>
	</div>
    <div class="section-content-wrapper">
        <div class="descriptive-text mb-3">{{$desc}}</div>
        <div id="id_{{$field.0}}_wrapper" class="mb-3">
            <label for="id_{{$field.0}}">{{$field.1}}{{if $field.5}}<sup class="required zuiqmid"> {{$field.5}}</sup>{{/if}}</label>
            <select class="form-control" name="{{$field.0}}" id="id_{{$field.0}}" onchange="location.href = '{{$urlRoot}}?theme=' + this.options[this.selectedIndex].value;">
                {{foreach $field.4 as $opt=>$val}}<option value="{{$opt}}" {{if $opt==$field.2}}selected="selected"{{/if}}>{{$val}}</option>{{/foreach}}
            </select>
            <small class="form-text text-muted">{{$field.3}}</small>
        </div>        
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