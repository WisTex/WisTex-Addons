<style>
textarea {
    display: inline-block;
    margin: 0;
    padding: .2em;
    width: auto;
    min-width: 30em;
    /* The max-width "100%" value fixes a weird issue where width is too wide by default and extends beyond 100% of the parent in some agents. */
    max-width: 100%;
    /* Height "auto" will allow the text area to expand vertically in size with a horizontal scrollbar if pre-existing content is added to the box before rendering. Remove this if you want a pre-set height. Use "em" to match the font size set in the website. */
    height: auto;
    /* Use "em" to define the height based on the text size set in your website and the text rows in the box, not a static pixel value. */
    min-height: 10em;
    /* Do not use "border" in textareas unless you want to remove the 3D box most browsers assign and flatten the box design. */
    /*border: 1px solid black;*/
    cursor: text;
    /* Some textareas have a light gray background by default anyway. */
    background-color: #eee;
    /* Overflow "auto" allows the box to start with no scrollbars but add them as content fills the box. */
    overflow: auto;
    /* Resize creates a tab in the lower right corner of textarea for most modern browsers and allows users to resize the box manually. Note: Resize isn't supported by most older agents and IE. */
    resize: both;
}
</style>
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