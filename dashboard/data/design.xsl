<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">


<xsl:output method="html" indent="yes"/>


<xsl:template match="/">
<html>
<head>
    <title>Event List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #5b0b1e;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #5b0b1e;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #5b0b1e;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        button {
            background-color: #5b0b1e;
            color: white;
            border: none;
            padding: 8px 15px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #7a1230;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .edit-button {
            background-color: #28a745;
        }
        .edit-button:hover {
            background-color: #218838;
        }
    </style>


        <script src="scripts.js"></script>


</head>
<body>


    <h2>University Events</h2>


    <table id="eventsTable">
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Event Name</th>
            <th>Location</th>
            <th>Department</th>
            <th>Event Type</th>
            <th>Target Audience</th>
            <th>Registration Link</th>
            <th>Agenda</th>
            <th>Contact Information</th>
            <th>Actions</th>
        </tr>


        <xsl:for-each select="events/event">
        <tr>
            <td><xsl:value-of select="date"/></td>
            <td><xsl:value-of select="time"/></td>
            <td><xsl:value-of select="eventName"/></td>
            <td><xsl:value-of select="location"/></td>
            <td><xsl:value-of select="department"/></td>
            <td><xsl:value-of select="eventType"/></td>
            <td><xsl:value-of select="targetAudience"/></td>
            <td><a href="{registrationLink}">Register</a></td>
            <td><xsl:value-of select="agenda"/></td>
            <td><xsl:value-of select="contactInformation"/></td>
            <td>
                <button class="edit-button" onclick="editRow(this)">Edit</button>
                <button onclick="deleteRow(this)">Delete</button>
            </td>
        </tr>
        </xsl:for-each>
    </table>
   


    <div class="button-container">
    <button onclick="addEvent()">Add Event</button>
    <button>Import Events</button>
    <button>Export Events</button>
</div>




</body>
</html>
</xsl:template>


</xsl:stylesheet>
