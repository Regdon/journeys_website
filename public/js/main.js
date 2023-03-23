function add_intermediate_station() {
    var newdiv = document.createElement("div");
    newdiv.className = "row";
    
    inner =  '<div class = "form-group col-md-9">'
    inner += '<lable for = "call_station[]">Intermediate Station: </lable>'
    inner += '<input type = "text" name = "call_station[]" class = "autocomplete form-control form-control-lg">'
    inner += '</div>'
    inner += '<div class = "form-group col-md-3">'
    inner += '<lable for = "call_station_time[]">Time: </lable>'
    inner += '<input type = "time" name = "call_station_time[]" class = "form-control form-control-lg"> '
    inner += '</div>'

    newdiv.innerHTML = inner

    document.getElementById("call-stations").appendChild(newdiv) 

    $(function() {
        $( ".autocomplete" ).autocomplete({
           source: "/journeys/stations/search",
           minLength: 2
        });
    })
}

function add_additional_unit() {
    var newdiv = document.createElement("div");
    newdiv.className = "form-group";
   
    inner = '<lable for = "unit[]">Additional Unit: </lable>'
    inner += '<input type = "text" name = "unit[]" class = "form-control form-control-lg" value = ""></input>'

    newdiv.innerHTML = inner

    document.getElementById("units").appendChild(newdiv) 
}