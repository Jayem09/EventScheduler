function deleteRow(button) {
    var row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
}


function editRow(button) {
    var row = button.parentNode.parentNode;
    var isEditing = button.textContent === "Save";


    if (isEditing) {
        for (let i = 0; i < row.cells.length - 1; i++) {
            let input = row.cells[i].querySelector("input") || row.cells[i].querySelector("select");
            if (input) {
                if (i === 7) { // Registration Link column
                    row.cells[i].innerHTML = `<a href="${input.value}" target="_blank">Register</a>`;
                } else {
                    row.cells[i].textContent = input.value;
                }
            }
        }
        button.textContent = "Edit";
    } else {
        for (let i = 0; i < row.cells.length - 1; i++) {
            let text = row.cells[i].innerText || row.cells[i].textContent;


            if (i === 0) { // Date
                row.cells[i].innerHTML = `<input type="date" value="${text}" style="width: 100%;">`;
            } else if (i === 4) { // Department
                row.cells[i].innerHTML = `
                    <select style="width: 100%;">
                        <option value="CITEC" ${text === "CITEC" ? "selected" : ""}>CITEC</option>
                        <option value="CMT" ${text === "CMT" ? "selected" : ""}>CMT</option>
                        <option value="CENAR" ${text === "CENAR" ? "selected" : ""}>CENAR</option>
                        <option value="CCJE" ${text === "CCJE" ? "selected" : ""}>CCJE</option>
                        <option value="CEAS" ${text === "CEAS" ? "selected" : ""}>CEAS</option>
                        <option value="JUNIOR HIGH SCHOOL" ${text === "JUNIOR HIGH SCHOOL" ? "selected" : ""}>JUNIOR HIGH SCHOOL</option>
                        <option value="SENIOR HIGH SCHOOL" ${text === "SENIOR HIGH SCHOOL" ? "selected" : ""}>SENIOR HIGH SCHOOL</option>
                    </select>`;
            } else if (i === 7) { // Registration Link
                let href = row.cells[i].querySelector("a")?.href || text;
                row.cells[i].innerHTML = `<input type="url" value="${href}" style="width: 100%;">`;
            } else if (i === 3) { // Location
                row.cells[i].innerHTML = `
                    <select style="width: 100%;">
                        <option value="UB LIPA MULTI - PURPOSE HALL (BUILDING C - 4TH FLOOR)" ${text === "UB LIPA MULTI - PURPOSE HALL (BUILDING C - 4TH FLOOR)" ? "selected" : ""}>UB LIPA MULTI - PURPOSE HALL (BUILDING C - 4TH FLOOR)</option>
                        <option value="UB LIPA TIRED LECTURE HALL (BUILDING C 4TH FLOOR)" ${text === "UB LIPA TIRED LECTURE HALL (BUILDING C 4TH FLOOR)" ? "selected" : ""}>UB LIPA TIRED LECTURE HALL (BUILDING C 4TH FLOOR)</option>
                        <option value="UB LIPA CAFE TRATTORIA (BUILDING C 2ND FLOOR)" ${text === "UB LIPA CAFE TRATTORIA (BUILDING C 2ND FLOOR)" ? "selected" : ""}>UB LIPA CAFE TRATTORIA (BUILDING C 2ND FLOOR)</option>
                        <option value="UB LIPA AUDIO VISUAL ROOM (BUILDING A - 3RD FLOOR)" ${text === "UB LIPA AUDIO VISUAL ROOM (BUILDING A - 3RD FLOOR)" ? "selected" : ""}>UB LIPA AUDIO VISUAL ROOM (BUILDING A - 3RD FLOOR)</option>
                        <option value="UB LIPA GYMANSIUM" ${text === "UB LIPA GYMNASIUM" ? "selected" : ""}>UB LIPA GYMNASIUM</option>
                        </select>`;
           
            }
        }
        button.textContent = "Save";
    }
}


function saveRow(button) {
    // Reuse editRow logic to save the row and switch to Edit mode
    editRow(button);
}


function addEvent() {
    var table = document.getElementById("eventsTable");
    var newRow = table.insertRow(-1); // Add new row at the end


    const headers = table.rows[0].cells;
    const numCols = headers.length;


    for (let i = 0; i < numCols; i++) {
        var newCell = newRow.insertCell(i);
        const headerText = headers[i].innerText;


        if (i === numCols - 1) {
            newCell.innerHTML = `
                <button class="edit-button" onclick="saveRow(this)">Save</button>
                <button class="delete-button" onclick="deleteRow(this)">Delete</button>`;
        } else if (headerText === "Date") {
            newCell.innerHTML = `<input type="date" style="width: 100%;">`;
        } else if (headerText === "Registration Link") {
            newCell.innerHTML = `<input type="url" placeholder="https://..." style="width: 100%;">`;
        } else if (headerText === "Department") {
            newCell.innerHTML = `
                <select style="width: 100%;">
                    <option value="CITEC">CITEC</option>
                    <option value="CMT">CMT</option>
                    <option value="CENAR">CENAR</option>
                    <option value="CCJE">CCJE</option>
                    <option value="CEAS">CEAS</option>
                    <option value="JUNIOR HIGH SCHOOL">JUNIOR HIGH SCHOOL</option>
                    <option value="SENIOR HIGH SCHOOL">SENIOR HIGH SCHOOL</option>
                </select>`;
            } else if (headerText === "Location") {
                newCell.innerHTML = `
                    <select style="width: 100%;">
                        <option value="UB LIPA MULTI - PURPOSE HALL (BUILDING C - 4TH FLOOR)">UB LIPA MULTI - PURPOSE HALL (BUILDING C - 4TH FLOOR)</option>
                        <option value="UB LIPA TIRED LECTURE HALL (BUILDING C 4TH FLOOR)">UB LIPA TIRED LECTURE HALL (BUILDING C 4TH FLOOR)</option>
                        <option value="UB LIPA CAFE TRATTORIA (BUILDING C 2ND FLOOR)">UB LIPA CAFE TRATTORIA (BUILDING C 2ND FLOOR)</option>
                        <option value="UB LIPA AUDIO VISUAL ROOM (BUILDING A - 3RD FLOOR)">UB LIPA AUDIO VISUAL ROOM (BUILDING A - 3RD FLOOR)</option>
                        <option value="UB LIPA GYMNASIUM">UB LIPA GYMNASIUM</option>
                        </select>`;
           
        } else {
            newCell.innerHTML = `<input type="text" style="width: 100%;">`;
        }
    }


    newRow.scrollIntoView({ behavior: "smooth" });
    newRow.cells[0].querySelector("input, select").focus();
}
