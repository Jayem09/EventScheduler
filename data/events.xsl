<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">
    <html>
      <head>
        <title>Upcoming Events - UB Lipa Event Scheduler</title>
        <link rel="stylesheet" href="css/style.css" />
      </head> 
      <body>

        <!-- HEADER -->
        <header class="topbar">
          <div class="logo-title">
            <!-- Removed the logo image and the redundant title -->
          </div>
        </header>

        <!-- EVENTS SECTION -->
        <section class="browse-events">

          <!-- Event Listings -->
          <table border="1" style="width:100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
              <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Event Name</th>
                <th>Location</th>
                <th>Organizer</th>
                <th>Contact Info</th>
                <th>Event Type</th>
                <th>Target Audience</th>
                <th>Registration Link</th>
                <th>Agenda</th>
              </tr>
            </thead>
            <tbody>
              <xsl:for-each select="EventSchedules/EventDetails">
                <tr>
                  <td><xsl:value-of select="Date" /></td>
                  <td><xsl:value-of select="Time/Start" /> - <xsl:value-of select="Time/End" /></td>
                  <td><xsl:value-of select="EventName" /></td>
                  <td><xsl:value-of select="Location/Venue" />, <xsl:value-of select="Location/Address" /></td>
                  <td><xsl:value-of select="Organizer" /></td>
                  <td><xsl:value-of select="ContactInformation/Email" /> | <xsl:value-of select="ContactInformation/Phone" /></td>
                  <td><xsl:value-of select="EventType" /></td>
                  <td><xsl:value-of select="TargetAudience" /></td>
                  <td><a href="{RegistrationLink}">Register Here</a></td>
                  <td>
                    <xsl:for-each select="Agenda/Session">
                      <p><xsl:value-of select="Time" />: <xsl:value-of select="Description" /></p>
                    </xsl:for-each>
                  </td>
                </tr>
              </xsl:for-each>
            </tbody>
          </table>
        </section>

      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
