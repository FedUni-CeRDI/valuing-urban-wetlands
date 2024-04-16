let filterOnFlag=false;
let alertCount=0;
export function openPanel(){
    document.getElementById(
        "aurin-sidebar").style.display = "block";
    document.getElementById(
        "map-viewport").className = "viewport";
    document.getElementById(
        "panel-open").style.display = "none";
}
function toggleFilter(){
    if(alertCount===0) {
        alertCount++;
        alert("Filters will be shown on Map");
    }
    else{
        alertCount++;
    }
    if(filterOnFlag){
        document.getElementById("filter-link").innerHTML="Hide Map Filters";
        document.getElementById(
            "landUse").style.display = "block";
        document.getElementById(
            "landUse-label").style.display = "block";
        document.getElementById(
            "protectionStatus").style.display = "block";
        document.getElementById(
            "protectionStatus-label").style.display = "block";
    }
    else {
        document.getElementById("filter-link").innerHTML="View Map Filters";
        document.getElementById(
            "landUse").style.display = "none";
        document.getElementById(
            "landUse-label").style.display = "none";
        document.getElementById(
            "protectionStatus").style.display = "none";
        document.getElementById(
            "protectionStatus-label").style.display = "none";
    }
    filterOnFlag=!filterOnFlag;
}

window.openPanel = openPanel;
window.toggleFilter = toggleFilter;