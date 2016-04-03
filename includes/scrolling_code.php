<div id="scrolling_text">
<marquee id="marquee" behavior="scroll" direction="up" height="180"  scrollamount="150" stop="0.5">
1)<br>
Event Type: Error<br>
Event Source: System Error<br>
Event Category: (102)<br>
Event ID: 1003<br>
Date: 11/26/2009<br>
Time: 3:26:24 PM<br>
User: N/A<br>
Computer: COMPUTER<br>
Description:<br>
Error code 0000004e, parameter1 00000099, parameter2 000fffff, parameter3 00000007, parameter4 00000000<br>
<br>
2)<br>
Event Type: Error<br>
Event Source: System Error<br>
Event Category: (102)<br>
Event ID: 1003<br>
Date: 11/28/2009<br>
Time: 12:14:02 AM<br>
User: N/A<br>
Computer: COMPUTER<br>
Description:<br>
Error code 1000000a, parameter1 003c0070, parameter2 00000002, parameter3 00000001, parameter4 804ea16f<br>
<br>
3)<br>
Event Type: Error<br>
Event Source: System Error<br>
Event Category: (102)<br>
Event ID: 1003<br>
Date: 11/29/2009<br>
Time: 2:48:48 AM<br>
User: N/A<br>
Computer: COMPUTER<br>
Description:<br>
Error code 1000008e, parameter1 c0000005, parameter2 bd1f132a, parameter3 b25d287c, parameter4 00000000<br>
<br>
4)<br>
Event Type: Error<br>
Event Source: System Error<br>
Event Category: (102)<br>
Event ID: 1003<br>
Date: 11/29/2009<br>
Time: 7:25:03 AM<br>
User: N/A<br>
Computer: COMPUTER<br>
Description:<br>
Error code 1000008e, parameter1 c0000005, parameter2 bd1f132a, parameter3 b518f87c, parameter4 00000000<br>
<br>
#!/usr/bin/ruby<br>
<br>
require 'tk'<br>
require 'net/ftp'<br>
<br>
# Close the connection and terminate pgm.<br>
def term(conn)<br>
 if conn<br>
 begin<br>
 conn.quit<br>
 ensure<br>
 conn.close<br>
 end<br>
 end<br>
 exit<br>
end<br>
<br>
# Display an error dialog.<br>
def thud(title, message)<br>
 Tk.messageBox('icon' => 'error', 'type' => 'ok', <br>
 'title' => title, 'message' => message)<br>
end<br>
<br>
# This is the login window. It pops up and asks for the remote host and the<br>
# user credentials, and a button to initiate the login when the fields are <br>
# ready.<br>
class LoginWindow<br>
 # Generate s label/entry pair for the login window. These will be <br>
 # appropriately gridded on row row inside par. Text box has width<br>
 # width and places its contents into the reference $ref. If $ispwd,<br>
 # treat it as a password entry box. Returns the text variable which<br>
 # gives access to the entry.<br>
 def genpair(row, text, width, ispwd=false)<br>
 tbut = TkLabel.new(@main, 'text' => text) {<br>
 grid('row' => row, 'column' => 0, 'sticky' => 'nse')<br>
 }<br>
 tvar = TkVariable.new('')<br>
 lab = TkEntry.new(@main) {<br>
 background 'white' <br>
 foreground 'black' <br>
 textvariable tvar<br>
 width width<br>
 grid('row' => row, 'column' => 1, 'sticky' => 'nsw')<br>
 }<br>
 lab.configure('show' => '*') if ispwd <br>
<br>
 return tvar<br>
 end<br>
<br>
 # Log into the remote host. If successful, start the directory loader.<br>
 # Modes are: 1: Anonymous, 2: User, 3: Return, which does anon if the<br>
 # user infor was not filled in, and user otw.<br>
 def do_login(mode)<br>
 host = @host.value<br>
 acct = @acct.value<br>
 password = @password.value<br>
<br>
 # Adjust user data by mode.<br>
 if mode == 1 || (mode == 3 &amp;&amp; acct == &quot;&quot; &amp;&amp; password == &quot;&quot;)<br>
 acct ='anonymous'<br>
 if password == &quot;&quot;<br>
 password = 'anonymous'<br>
 end<br>
 end<br>
<br>
 # Make sure we're all filled in.<br>
 if host == &quot;&quot; || acct == &quot;&quot; || password == &quot;&quot;<br>
 thud('No Login Info', <br>
 &quot;You must provide a hostname and login credentials.&quot;)<br>
 return<br>
 end<br>
<br>
 # Attempt to connect to the remote host and log in<br>
 begin<br>
 @conn = Net::FTP.new(host, acct, password)<br>
 @conn.passive = true<br>
 rescue<br>
 thud(&quot;Login Failed&quot;, $!)<br>
 @conn = nil<br>
 return<br>
 end<br>
<br>
 # Display the listing in the window.<br>
 @listwin.setconn(@conn)<br>
 @main.destroy()<br>
 end<br>
<br>
 def initialize(main, listwin, titfont, titcolor)<br>
 @main = TkToplevel.new(main)<br>
 @main.title('FTP Login')<br>
<br>
 # Listing window.<br>
 @listwin = listwin<br>
 @conn = nil<br>
<br>
 # This counts through the rows, which makes it easier to modify<br>
 # the program.<br>
 row = -1<br>
<br>
 # Label at the top of window.<br>
 toplab = TkLabel.new(@main) {<br>
 text &quot;FTP Server Login&quot;<br>
 justify 'center'<br>
 font titfont<br>
 foreground titcolor<br>
 grid('row' => (row += 1), 'column' => 0, 'columnspan' => 2, <br>
 'sticky' => 'news')<br>
 }<br>
<br>
 # Hostname entry<br>
 @host = genpair(row += 1, 'Host:', 25)<br>
<br>
 # Login buttons, in a frame for layout.<br>
 bframe = TkFrame.new(@main) {<br>
 grid('row' => (row += 1), 'column' => 0, 'columnspan' => 2, <br>
 'sticky' => 'news')<br>
 }<br>
 TkButton.new(bframe, 'command' => proc { self.do_login(1) }) {<br>
 text 'Anon. Login'<br>
 pack('side' => 'left', 'expand' => 'yes', 'fill' => 'both')<br>
 }<br>
 TkButton.new(bframe, 'command' => proc { self.do_login(2) }) {<br>
 text 'User Login'<br>
 pack('side' => 'left', 'expand' => 'yes', 'fill' => 'both')<br>
 }<br>
<br>
 # Login and password entries.<br>
 @acct = genpair(row += 1, 'Login:', 15)<br>
 @password = genpair(row += 1, 'Password:', 15, true)<br>
<br>
 stop = TkButton.new(@main, 'command' => proc { term(@conn) } ) {<br>
 text 'Exit'<br>
 grid('row' => (row += 1), 'column' => 0, 'columnspan' => 2, <br>
 'sticky' => 'news')<br>
 }<br>
<br>
 # CR same as pushing login.<br>
 @main.bind('Return', proc { self.do_login(3) })<br>
 end<br>
end<br>
<br>
# This is a window containing the file listing.<br>
class FileWindow < TkFrame<br>
 def initialize(main)<br>
 super<br>
<br>
 # Set up the title appearance.<br>
 titfont = 'arial 16 bold'<br>
 titcolor = '#228800'<br>
<br>
 @conn = nil<br>
<br>
 # Label at top.<br>
 TkLabel.new(self) {<br>
 text 'FTP Download Agent'<br>
 justify 'center'<br>
 font titfont<br>
 foreground titcolor<br>
 pack('side' => 'top', 'fill' => 'x')<br>
 }<br>
<br>
 # Status label.<br>
 @statuslab = TkLabel.new(self) {<br>
 text 'Not Logged In'<br>
 justify 'center'<br>
 pack('side' => 'top', 'fill' => 'x')<br>
 }<br>
<br>
 # Exit button<br>
 TkButton.new(self) {<br>
 text 'Exit'<br>
 command { term(@conn) }<br>
 pack('side'=> 'bottom', 'fill' => 'x')<br>
 }<br>
<br>
 # List area with scroll bar. The list area is disabled since we<br>
 # don't want the user to type into it.<br>
 @listarea = TkText.new(self) {<br>
 height 10<br>
 width 40<br>
 cursor 'sb_left_arrow'<br>
 state 'disabled'<br>
 pack('side' => 'left')<br>
 yscrollbar(TkScrollbar.new).pack('side' => 'right', 'fill' => 'y')<br>
 }<br>
<br>
 # Bind the system exit button to our exit.<br>
 main.protocol('WM_DELETE_WINDOW', proc { term(@conn) } )<br>
<br>
 # Create the login window.<br>
 LoginWindow.new(main, self, titfont, titcolor)<br>
 end<br>
<br>
 # Change the color of a tag for entering and leaving. Unfortunately, there<br>
 # is no active color for tags in a text box.<br>
 def recolor(tag, color)<br>
 @listarea.tag_configure(tag, 'foreground' => color)<br>
 end<br>
<br>
 # Do a CD and load the contents. If there is no directory name, skip<br>
 # the CD.<br>
 def load_dir(dir)<br>
 if dir<br>
 begin<br>
 @conn.chdir(dir)<br>
 rescue<br>
 thud('No ' + dir, $!)<br>
 end<br>
 @statuslab.configure('text' => &quot;[Loading &quot; + dir + &quot;]&quot;)<br>
 else<br>
 @statuslab.configure('text' => '[Loading Home Dir]')<br>
 end<br>
 update<br>
<br>
 # Get the list of files.<br>
 files = [ ]<br>
 dirs = [ ]<br>
 sawdots = false<br>
 @conn.list() do |line|<br>
 # Real lines start with the perm bits. And we don't want specials.<br>
 if line =~ /^[\-d]([r\-][w\-][x\-]){3}/<br>
 # Extract the useful parts, toss the bones. The limit keeps us from<br>
 # dividing file names containing spaces.<br>
 parts = line.split(/\s+/, 9)<br>
 if parts.length >= 9<br>
 fn = parts.pop()<br>
 sawdots = true if fn == '..'<br>
 if parts[0][0..0] == 'd'<br>
 dirs.push(fn)<br>
 else<br>
 files.push(fn)<br>
 end<br>
 end<br>
 end<br>
 end<br>
<br>
 # Add .. if not present, then sort the list.<br>
 dirs.push('..') unless sawdots<br>
 files.sort!<br>
 dirs.sort!<br>
<br>
 # Clear the old contents from the directory listing box.<br>
 @listarea.configure('state' => 'normal')<br>
 @listarea.delete('1.0', 'end')<br>
<br>
 # Fill in the directories. Bind for directory load (us).<br>
 ct = 0<br>
 while fn = dirs.shift<br>
 tagname = &quot;fn&quot; + ct.to_s<br>
 @listarea.insert('end', fn+&quot;\n&quot;, tagname)<br>
 @listarea.tag_configure(tagname, 'foreground' => '#4444FF')<br>
 @listarea.tag_bind(tagname, 'Button-1', <br>
 proc { |f| self.load_dir(f) }, fn)<br>
 @listarea.tag_bind(tagname, 'Enter', <br>
 proc { |t| self.recolor(t, '#0000aa') },<br>
 tagname)<br>
 @listarea.tag_bind(tagname, 'Leave', <br>
 proc { |t| self.recolor(t, '#4444ff') },<br>
 tagname)<br>
 ct += 1<br>
 end<br>
<br>
 # Fill in the files. Bind for download.<br>
 while fn = files.shift<br>
 tagname = &quot;fn&quot; + ct.to_s<br>
 @listarea.insert('end', fn+&quot;\n&quot;, tagname)<br>
 @listarea.tag_configure(tagname, 'foreground' => 'red')<br>
 @listarea.tag_bind(tagname, 'Button-1', <br>
 proc { |f| self.dld_file(f) }, fn)<br>
 @listarea.tag_bind(tagname, 'Enter', <br>
 proc { |t| self.recolor(t, '#880000') },<br>
 tagname)<br>
 @listarea.tag_bind(tagname, 'Leave', <br>
 proc { |t| self.recolor(t, 'red') },<br>
 tagname)<br>
 ct += 1<br>
 end<br>
<br>
 # Lock it up so the user can't mess with it.<br>
 @listarea.configure('state' => 'disabled')<br>
<br>
 # Update the status label.<br>
 begin<br>
 loc = @conn.pwd()<br>
 rescue<br>
 thud('PWD Failed', $!)<br>
 loc = '???'<br>
 end<br>
 @statuslab.configure('text' => loc)<br>
 end<br>
<br>
 # Download the file.<br>
 def dld_file(fn)<br>
 # Announce.<br>
 @statuslab.configure('text' => &quot;[Retrieving &quot; + fn + &quot;]&quot;)<br>
 update<br>
<br>
 # Get the file.<br>
 begin<br>
 @conn.getbinaryfile(fn)<br>
 rescue<br>
 thud('DLD Failed', fn + ': ' + $!)<br>
 @statuslab.configure('text' => '')<br>
 else<br>
 @statuslab.configure('text' => 'Got ' + fn)<br>
 end<br>
 end<br>
<br>
 # This is a hook that the login window calls after a successful login.<br>
 # The login window makes the connection and attempts to login. When this<br>
 # succeeds, it calls setconn() and destroys itself. Setconn records the<br>
 # connection (which the login box created), then does the initial<br>
 # directory load.<br>
 def setconn(conn)<br>
 @conn = conn<br>
 load_dir(nil)<br>
 end<br>
end<br>
<br>
# Create the main window, set the default colors, create the GUI, then<br>
# fire the sucker up.<br>
BG = '#E6E6FA'<br>
root = TkRoot.new('background' => BG) { title &quot;FTP Download&quot; }<br>
TkOption.add(&quot;*background&quot;, BG)<br>
TkOption.add(&quot;*activebackground&quot;, '#FFE6FA')<br>
TkOption.add(&quot;*foreground&quot;, '#0000FF')<br>
TkOption.add(&quot;*activeforeground&quot;, '#0000FF')<br>
FileWindow.new(root).pack()<br>
<br>
Tk.mainloop<br>
<br>
#!/usr/bin/ruby<br>
<br>
require 'tk'<br>
require 'net/ftp'<br>
<br>
# Close the connection and terminate pgm.<br>
def term(conn)<br>
 if conn<br>
 begin<br>
 conn.quit<br>
 ensure<br>
 conn.close<br>
 end<br>
 end<br>
 exit<br>
end<br>
<br>
# Display an error dialog.<br>
def thud(title, message)<br>
 Tk.messageBox('icon' => 'error', 'type' => 'ok', <br>
 'title' => title, 'message' => message)<br>
end<br>
<br>
# This is the login window. It pops up and asks for the remote host and the<br>
# user credentials, and a button to initiate the login when the fields are <br>
# ready.<br>
class LoginWindow<br>
 # Generate s label/entry pair for the login window. These will be <br>
 # appropriately gridded on row row inside par. Text box has width<br>
 # width and places its contents into the reference $ref. If $ispwd,<br>
 # treat it as a password entry box. Returns the text variable which<br>
 # gives access to the entry.<br>
 def genpair(row, text, width, ispwd=false)<br>
 tbut = TkLabel.new(@main, 'text' => text) {<br>
 grid('row' => row, 'column' => 0, 'sticky' => 'nse')<br>
 }<br>
 tvar = TkVariable.new('')<br>
 lab = TkEntry.new(@main) {<br>
 background 'white' <br>
 foreground 'black' <br>
 textvariable tvar<br>
 width width<br>
 grid('row' => row, 'column' => 1, 'sticky' => 'nsw')<br>
 }<br>
 lab.configure('show' => '*') if ispwd <br>
<br>
 return tvar<br>
 end<br>
<br>
 # Log into the remote host. If successful, start the directory loader.<br>
 # Modes are: 1: Anonymous, 2: User, 3: Return, which does anon if the<br>
 # user infor was not filled in, and user otw.<br>
 def do_login(mode)<br>
 host = @host.value<br>
 acct = @acct.value<br>
 password = @password.value<br>
<br>
 # Adjust user data by mode.<br>
 if mode == 1 || (mode == 3 &amp;&amp; acct == &quot;&quot; &amp;&amp; password == &quot;&quot;)<br>
 acct ='anonymous'<br>
 if password == &quot;&quot;<br>
 password = 'anonymous'<br>
 end<br>
 end<br>
<br>
 # Make sure we're all filled in.<br>
 if host == &quot;&quot; || acct == &quot;&quot; || password == &quot;&quot;<br>
 thud('No Login Info', <br>
 &quot;You must provide a hostname and login credentials.&quot;)<br>
 return<br>
 end<br>
<br>
 # Attempt to connect to the remote host and log in<br>
 begin<br>
 @conn = Net::FTP.new(host, acct, password)<br>
 @conn.passive = true<br>
 rescue<br>
 thud(&quot;Login Failed&quot;, $!)<br>
 @conn = nil<br>
 return<br>
 end<br>
<br>
 # Display the listing in the window.<br>
 @listwin.setconn(@conn)<br>
 @main.destroy()<br>
 end<br>
<br>
 def initialize(main, listwin, titfont, titcolor)<br>
 @main = TkToplevel.new(main)<br>
 @main.title('FTP Login')<br>
<br>
 # Listing window.<br>
 @listwin = listwin<br>
 @conn = nil<br>
<br>
 # This counts through the rows, which makes it easier to modify<br>
 # the program.<br>
 row = -1<br>
<br>
 # Label at the top of window.<br>
 toplab = TkLabel.new(@main) {<br>
 text &quot;FTP Server Login&quot;<br>
 justify 'center'<br>
 font titfont<br>
 foreground titcolor<br>
 grid('row' => (row += 1), 'column' => 0, 'columnspan' => 2, <br>
 'sticky' => 'news')<br>
 }<br>
<br>
 # Hostname entry<br>
 @host = genpair(row += 1, 'Host:', 25)<br>
<br>
 # Login buttons, in a frame for layout.<br>
 bframe = TkFrame.new(@main) {<br>
 grid('row' => (row += 1), 'column' => 0, 'columnspan' => 2, <br>
 'sticky' => 'news')<br>
 }<br>
 TkButton.new(bframe, 'command' => proc { self.do_login(1) }) {<br>
 text 'Anon. Login'<br>
 pack('side' => 'left', 'expand' => 'yes', 'fill' => 'both')<br>
 }<br>
 TkButton.new(bframe, 'command' => proc { self.do_login(2) }) {<br>
 text 'User Login'<br>
 pack('side' => 'left', 'expand' => 'yes', 'fill' => 'both')<br>
 }<br>
<br>
 # Login and password entries.<br>
 @acct = genpair(row += 1, 'Login:', 15)<br>
 @password = genpair(row += 1, 'Password:', 15, true)<br>
<br>
 stop = TkButton.new(@main, 'command' => proc { term(@conn) } ) {<br>
 text 'Exit'<br>
 grid('row' => (row += 1), 'column' => 0, 'columnspan' => 2, <br>
 'sticky' => 'news')<br>
 }<br>
<br>
 # CR same as pushing login.<br>
 @main.bind('Return', proc { self.do_login(3) })<br>
 end<br>
end<br>
<br>
# This is a window containing the file listing.<br>
class FileWindow < TkFrame<br>
 def initialize(main)<br>
 super<br>
<br>
 # Set up the title appearance.<br>
 titfont = 'arial 16 bold'<br>
 titcolor = '#228800'<br>
<br>
 @conn = nil<br>
<br>
 # Label at top.<br>
 TkLabel.new(self) {<br>
 text 'FTP Download Agent'<br>
 justify 'center'<br>
 font titfont<br>
 foreground titcolor<br>
 pack('side' => 'top', 'fill' => 'x')<br>
 }<br>
<br>
 # Status label.<br>
 @statuslab = TkLabel.new(self) {<br>
 text 'Not Logged In'<br>
 justify 'center'<br>
 pack('side' => 'top', 'fill' => 'x')<br>
 }<br>
<br>
 # Exit button<br>
 TkButton.new(self) {<br>
 text 'Exit'<br>
 command { term(@conn) }<br>
 pack('side'=> 'bottom', 'fill' => 'x')<br>
 }<br>
<br>
 # List area with scroll bar. The list area is disabled since we<br>
 # don't want the user to type into it.<br>
 @listarea = TkText.new(self) {<br>
 height 10<br>
 width 40<br>
 cursor 'sb_left_arrow'<br>
 state 'disabled'<br>
 pack('side' => 'left')<br>
 yscrollbar(TkScrollbar.new).pack('side' => 'right', 'fill' => 'y')<br>
 }<br>
<br>
 # Bind the system exit button to our exit.<br>
 main.protocol('WM_DELETE_WINDOW', proc { term(@conn) } )<br>
<br>
 # Create the login window.<br>
 LoginWindow.new(main, self, titfont, titcolor)<br>
 end<br>
<br>
 # Change the color of a tag for entering and leaving. Unfortunately, there<br>
 # is no active color for tags in a text box.<br>
 def recolor(tag, color)<br>
 @listarea.tag_configure(tag, 'foreground' => color)<br>
 end<br>
<br>
 # Do a CD and load the contents. If there is no directory name, skip<br>
 # the CD.<br>
 def load_dir(dir)<br>
 if dir<br>
 begin<br>
 @conn.chdir(dir)<br>
 rescue<br>
 thud('No ' + dir, $!)<br>
 end<br>
 @statuslab.configure('text' => &quot;[Loading &quot; + dir + &quot;]&quot;)<br>
 else<br>
 @statuslab.configure('text' => '[Loading Home Dir]')<br>
 end<br>
 update<br>
<br>
 # Get the list of files.<br>
 files = [ ]<br>
 dirs = [ ]<br>
 sawdots = false<br>
 @conn.list() do |line|<br>
 # Real lines start with the perm bits. And we don't want specials.<br>
 if line =~ /^[\-d]([r\-][w\-][x\-]){3}/<br>
 # Extract the useful parts, toss the bones. The limit keeps us from<br>
 # dividing file names containing spaces.<br>
 parts = line.split(/\s+/, 9)<br>
 if parts.length >= 9<br>
 fn = parts.pop()<br>
 sawdots = true if fn == '..'<br>
 if parts[0][0..0] == 'd'<br>
 dirs.push(fn)<br>
 else<br>
 files.push(fn)<br>
 end<br>
 end<br>
 end<br>
 end<br>
<br>
 # Add .. if not present, then sort the list.<br>
 dirs.push('..') unless sawdots<br>
 files.sort!<br>
 dirs.sort!<br>
<br>
 # Clear the old contents from the directory listing box.<br>
 @listarea.configure('state' => 'normal')<br>
 @listarea.delete('1.0', 'end')<br>
<br>
 # Fill in the directories. Bind for directory load (us).<br>
 ct = 0<br>
 while fn = dirs.shift<br>
 tagname = &quot;fn&quot; + ct.to_s<br>
 @listarea.insert('end', fn+&quot;\n&quot;, tagname)<br>
 @listarea.tag_configure(tagname, 'foreground' => '#4444FF')<br>
 @listarea.tag_bind(tagname, 'Button-1', <br>
 proc { |f| self.load_dir(f) }, fn)<br>
 @listarea.tag_bind(tagname, 'Enter', <br>
 proc { |t| self.recolor(t, '#0000aa') },<br>
 tagname)<br>
 @listarea.tag_bind(tagname, 'Leave', <br>
 proc { |t| self.recolor(t, '#4444ff') },<br>
 tagname)<br>
 ct += 1<br>
 end<br>
<br>
 # Fill in the files. Bind for download.<br>
 while fn = files.shift<br>
 tagname = &quot;fn&quot; + ct.to_s<br>
 @listarea.insert('end', fn+&quot;\n&quot;, tagname)<br>
 @listarea.tag_configure(tagname, 'foreground' => 'red')<br>
 @listarea.tag_bind(tagname, 'Button-1', <br>
 proc { |f| self.dld_file(f) }, fn)<br>
 @listarea.tag_bind(tagname, 'Enter', <br>
 proc { |t| self.recolor(t, '#880000') },<br>
 tagname)<br>
 @listarea.tag_bind(tagname, 'Leave', <br>
 proc { |t| self.recolor(t, 'red') },<br>
 tagname)<br>
 ct += 1<br>
 end<br>
<br>
 # Lock it up so the user can't mess with it.<br>
 @listarea.configure('state' => 'disabled')<br>
<br>
 # Update the status label.<br>
 begin<br>
 loc = @conn.pwd()<br>
 rescue<br>
 thud('PWD Failed', $!)<br>
 loc = '???'<br>
 end<br>
 @statuslab.configure('text' => loc)<br>
 end<br>
<br>
 # Download the file.<br>
 def dld_file(fn)<br>
 # Announce.<br>
 @statuslab.configure('text' => &quot;[Retrieving &quot; + fn + &quot;]&quot;)<br>
 update<br>
<br>
 # Get the file.<br>
 begin<br>
 @conn.getbinaryfile(fn)<br>
 rescue<br>
 thud('DLD Failed', fn + ': ' + $!)<br>
 @statuslab.configure('text' => '')<br>
 else<br>
 @statuslab.configure('text' => 'Got ' + fn)<br>
 end<br>
 end<br>
<br>
 # This is a hook that the login window calls after a successful login.<br>
 # The login window makes the connection and attempts to login. When this<br>
 # succeeds, it calls setconn() and destroys itself. Setconn records the<br>
 # connection (which the login box created), then does the initial<br>
 # directory load.<br>
 def setconn(conn)<br>
 @conn = conn<br>
 load_dir(nil)<br>
 end<br>
end<br>
<br>
# Create the main window, set the default colors, create the GUI, then<br>
# fire the sucker up.<br>
BG = '#E6E6FA'<br>
root = TkRoot.new('background' => BG) { title &quot;FTP Download&quot; }<br>
TkOption.add(&quot;*background&quot;, BG)<br>
TkOption.add(&quot;*activebackground&quot;, '#FFE6FA')<br>
TkOption.add(&quot;*foreground&quot;, '#0000FF')<br>
TkOption.add(&quot;*activeforeground&quot;, '#0000FF')<br>
FileWindow.new(root).pack()<br>
<br>
Tk.mainloop<br>
<br>
#!/usr/bin/ruby<br>
<br>
require 'tk'<br>
require 'net/ftp'<br>
<br>
# Close the connection and terminate pgm.<br>
def term(conn)<br>
 if conn<br>
 begin<br>
 conn.quit<br>
 ensure<br>
 conn.close<br>
 end<br>
 end<br>
 exit<br>
end<br>
<br>
# Display an error dialog.<br>
def thud(title, message)<br>
 Tk.messageBox('icon' => 'error', 'type' => 'ok', <br>
 'title' => title, 'message' => message)<br>
end<br>
<br>
# This is the login window. It pops up and asks for the remote host and the<br>
# user credentials, and a button to initiate the login when the fields are <br>
# ready.<br>
class LoginWindow<br>
 # Generate s label/entry pair for the login window. These will be <br>
 # appropriately gridded on row row inside par. Text box has width<br>
 # width and places its contents into the reference $ref. If $ispwd,<br>
 # treat it as a password entry box. Returns the text variable which<br>
 # gives access to the entry.<br>
 def genpair(row, text, width, ispwd=false)<br>
 tbut = TkLabel.new(@main, 'text' => text) {<br>
 grid('row' => row, 'column' => 0, 'sticky' => 'nse')<br>
 }<br>
 tvar = TkVariable.new('')<br>
 lab = TkEntry.new(@main) {<br>
 background 'white' <br>
 foreground 'black' <br>
 textvariable tvar<br>
 width width<br>
 grid('row' => row, 'column' => 1, 'sticky' => 'nsw')<br>
 }<br>
 lab.configure('show' => '*') if ispwd <br>
<br>
 return tvar<br>
 end<br>
<br>
 # Log into the remote host. If successful, start the directory loader.<br>
 # Modes are: 1: Anonymous, 2: User, 3: Return, which does anon if the<br>
 # user infor was not filled in, and user otw.<br>
 def do_login(mode)<br>
 host = @host.value<br>
 acct = @acct.value<br>
 password = @password.value<br>
<br>
 # Adjust user data by mode.<br>
 if mode == 1 || (mode == 3 &amp;&amp; acct == &quot;&quot; &amp;&amp; password == &quot;&quot;)<br>
 acct ='anonymous'<br>
 if password == &quot;&quot;<br>
 password = 'anonymous'<br>
 end<br>
 end<br>
<br>
 # Make sure we're all filled in.<br>
 if host == &quot;&quot; || acct == &quot;&quot; || password == &quot;&quot;<br>
 thud('No Login Info', <br>
 &quot;You must provide a hostname and login credentials.&quot;)<br>
 return<br>
 end<br>
<br>
 # Attempt to connect to the remote host and log in<br>
 begin<br>
 @conn = Net::FTP.new(host, acct, password)<br>
 @conn.passive = true<br>
 rescue<br>
 thud(&quot;Login Failed&quot;, $!)<br>
 @conn = nil<br>
 return<br>
 end<br>
<br>
 # Display the listing in the window.<br>
 @listwin.setconn(@conn)<br>
 @main.destroy()<br>
 end<br>
<br>
 def initialize(main, listwin, titfont, titcolor)<br>
 @main = TkToplevel.new(main)<br>
 @main.title('FTP Login')<br>
<br>
 # Listing window.<br>
 @listwin = listwin<br>
 @conn = nil<br>
<br>
 # This counts through the rows, which makes it easier to modify<br>
 # the program.<br>
 row = -1<br>
<br>
 # Label at the top of window.<br>
 toplab = TkLabel.new(@main) {<br>
 text &quot;FTP Server Login&quot;<br>
 justify 'center'<br>
 font titfont<br>
 foreground titcolor<br>
 grid('row' => (row += 1), 'column' => 0, 'columnspan' => 2, <br>
 'sticky' => 'news')<br>
 }<br>
<br>
 # Hostname entry<br>
 @host = genpair(row += 1, 'Host:', 25)<br>
<br>
 # Login buttons, in a frame for layout.<br>
 bframe = TkFrame.new(@main) {<br>
 grid('row' => (row += 1), 'column' => 0, 'columnspan' => 2, <br>
 'sticky' => 'news')<br>
 }<br>
 TkButton.new(bframe, 'command' => proc { self.do_login(1) }) {<br>
 text 'Anon. Login'<br>
 pack('side' => 'left', 'expand' => 'yes', 'fill' => 'both')<br>
 }<br>
 TkButton.new(bframe, 'command' => proc { self.do_login(2) }) {<br>
 text 'User Login'<br>
 pack('side' => 'left', 'expand' => 'yes', 'fill' => 'both')<br>
 }<br>
<br>
 # Login and password entries.<br>
 @acct = genpair(row += 1, 'Login:', 15)<br>
 @password = genpair(row += 1, 'Password:', 15, true)<br>
<br>
 stop = TkButton.new(@main, 'command' => proc { term(@conn) } ) {<br>
 text 'Exit'<br>
 grid('row' => (row += 1), 'column' => 0, 'columnspan' => 2, <br>
 'sticky' => 'news')<br>
 }<br>
<br>
 # CR same as pushing login.<br>
 @main.bind('Return', proc { self.do_login(3) })<br>
 end<br>
end<br>
<br>
# This is a window containing the file listing.<br>
class FileWindow < TkFrame<br>
 def initialize(main)<br>
 super<br>
<br>
 # Set up the title appearance.<br>
 titfont = 'arial 16 bold'<br>
 titcolor = '#228800'<br>
<br>
 @conn = nil<br>
<br>
 # Label at top.<br>
 TkLabel.new(self) {<br>
 text 'FTP Download Agent'<br>
 justify 'center'<br>
 font titfont<br>
 foreground titcolor<br>
 pack('side' => 'top', 'fill' => 'x')<br>
 }<br>
<br>
 # Status label.<br>
 @statuslab = TkLabel.new(self) {<br>
 text 'Not Logged In'<br>
 justify 'center'<br>
 pack('side' => 'top', 'fill' => 'x')<br>
 }<br>
<br>
 # Exit button<br>
 TkButton.new(self) {<br>
 text 'Exit'<br>
 command { term(@conn) }<br>
 pack('side'=> 'bottom', 'fill' => 'x')<br>
 }<br>
<br>
 # List area with scroll bar. The list area is disabled since we<br>
 # don't want the user to type into it.<br>
 @listarea = TkText.new(self) {<br>
 height 10<br>
 width 40<br>
 cursor 'sb_left_arrow'<br>
 state 'disabled'<br>
 pack('side' => 'left')<br>
 yscrollbar(TkScrollbar.new).pack('side' => 'right', 'fill' => 'y')<br>
 }<br>
<br>
 # Bind the system exit button to our exit.<br>
 main.protocol('WM_DELETE_WINDOW', proc { term(@conn) } )<br>
<br>
 # Create the login window.<br>
 LoginWindow.new(main, self, titfont, titcolor)<br>
 end<br>
<br>
 # Change the color of a tag for entering and leaving. Unfortunately, there<br>
 # is no active color for tags in a text box.<br>
 def recolor(tag, color)<br>
 @listarea.tag_configure(tag, 'foreground' => color)<br>
 end<br>
<br>
 # Do a CD and load the contents. If there is no directory name, skip<br>
 # the CD.<br>
 def load_dir(dir)<br>
 if dir<br>
 begin<br>
 @conn.chdir(dir)<br>
 rescue<br>
 thud('No ' + dir, $!)<br>
 end<br>
 @statuslab.configure('text' => &quot;[Loading &quot; + dir + &quot;]&quot;)<br>
 else<br>
 @statuslab.configure('text' => '[Loading Home Dir]')<br>
 end<br>
 update<br>
<br>
 # Get the list of files.<br>
 files = [ ]<br>
 dirs = [ ]<br>
 sawdots = false<br>
 @conn.list() do |line|<br>
 # Real lines start with the perm bits. And we don't want specials.<br>
 if line =~ /^[\-d]([r\-][w\-][x\-]){3}/<br>
 # Extract the useful parts, toss the bones. The limit keeps us from<br>
 # dividing file names containing spaces.<br>
 parts = line.split(/\s+/, 9)<br>
 if parts.length >= 9<br>
 fn = parts.pop()<br>
 sawdots = true if fn == '..'<br>
 if parts[0][0..0] == 'd'<br>
 dirs.push(fn)<br>
 else<br>
 files.push(fn)<br>
 end<br>
 end<br>
 end<br>
 end<br>
<br>
 # Add .. if not present, then sort the list.<br>
 dirs.push('..') unless sawdots<br>
 files.sort!<br>
 dirs.sort!<br>
<br>
 # Clear the old contents from the directory listing box.<br>
 @listarea.configure('state' => 'normal')<br>
 @listarea.delete('1.0', 'end')<br>
<br>
 # Fill in the directories. Bind for directory load (us).<br>
 ct = 0<br>
 while fn = dirs.shift<br>
 tagname = &quot;fn&quot; + ct.to_s<br>
 @listarea.insert('end', fn+&quot;\n&quot;, tagname)<br>
 @listarea.tag_configure(tagname, 'foreground' => '#4444FF')<br>
 @listarea.tag_bind(tagname, 'Button-1', <br>
 proc { |f| self.load_dir(f) }, fn)<br>
 @listarea.tag_bind(tagname, 'Enter', <br>
 proc { |t| self.recolor(t, '#0000aa') },<br>
 tagname)<br>
 @listarea.tag_bind(tagname, 'Leave', <br>
 proc { |t| self.recolor(t, '#4444ff') },<br>
 tagname)<br>
 ct += 1<br>
 end<br>
<br>
 # Fill in the files. Bind for download.<br>
 while fn = files.shift<br>
 tagname = &quot;fn&quot; + ct.to_s<br>
 @listarea.insert('end', fn+&quot;\n&quot;, tagname)<br>
 @listarea.tag_configure(tagname, 'foreground' => 'red')<br>
 @listarea.tag_bind(tagname, 'Button-1', <br>
 proc { |f| self.dld_file(f) }, fn)<br>
 @listarea.tag_bind(tagname, 'Enter', <br>
 proc { |t| self.recolor(t, '#880000') },<br>
 tagname)<br>
 @listarea.tag_bind(tagname, 'Leave', <br>
 proc { |t| self.recolor(t, 'red') },<br>
 tagname)<br>
 ct += 1<br>
 end<br>
<br>
 # Lock it up so the user can't mess with it.<br>
 @listarea.configure('state' => 'disabled')<br>
<br>
 # Update the status label.<br>
 begin<br>
 loc = @conn.pwd()<br>
 rescue<br>
 thud('PWD Failed', $!)<br>
 loc = '???'<br>
 end<br>
 @statuslab.configure('text' => loc)<br>
 end<br>
<br>
 # Download the file.<br>
 def dld_file(fn)<br>
 # Announce.<br>
 @statuslab.configure('text' => &quot;[Retrieving &quot; + fn + &quot;]&quot;)<br>
 update<br>
<br>
 # Get the file.<br>
 begin<br>
 @conn.getbinaryfile(fn)<br>
 rescue<br>
 thud('DLD Failed', fn + ': ' + $!)<br>
 @statuslab.configure('text' => '')<br>
 else<br>
 @statuslab.configure('text' => 'Got ' + fn)<br>
 end<br>
 end<br>
<br>
 # This is a hook that the login window calls after a successful login.<br>
 # The login window makes the connection and attempts to login. When this<br>
 # succeeds, it calls setconn() and destroys itself. Setconn records the<br>
 # connection (which the login box created), then does the initial<br>
 # directory load.<br>
 def setconn(conn)<br>
 @conn = conn<br>
 load_dir(nil)<br>
 end<br>
end<br>
<br>
# Create the main window, set the default colors, create the GUI, then<br>
# fire the sucker up.<br>
BG = '#E6E6FA'<br>
root = TkRoot.new('background' => BG) { title &quot;FTP Download&quot; }<br>
TkOption.add(&quot;*background&quot;, BG)<br>
TkOption.add(&quot;*activebackground&quot;, '#FFE6FA')<br>
TkOption.add(&quot;*foreground&quot;, '#0000FF')<br>
TkOption.add(&quot;*activeforeground&quot;, '#0000FF')<br>
FileWindow.new(root).pack()<br>
<br>
Tk.mainloop<br>

</script></div></body></html>
</marquee>