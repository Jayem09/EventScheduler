<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" indent="yes"/>

  <!-- Match the root element (events) -->
  <xsl:template match="/">
    <html>
      <head>
        <title>Event List</title>
        <style>
          table { width: 100%; border-collapse: collapse; margin-top: 20px; }
          th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
          th { background-color: #f4f4f4; }
          tr:nth-child(even) { background-color: #f9f9f9; }
          button { padding: 8px 15px; cursor: pointer; }
        </style>
      </head>
      <body>
        <h2>University Events</h2>

        <table>
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
            </tr>
          </xsl:for-each>
        </table>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
