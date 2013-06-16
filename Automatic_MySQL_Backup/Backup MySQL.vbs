BACKUP_DIR = "C:\Documents and Settings\music\My Documents\My Dropbox"	' Dropbox path should go here
MYSQLDUMP_PATH = """C:\xampp\mysql\bin\mysqldump.exe"""
DBUSER = "arhip"
DBPASSWORD = "12345"
BACKUP_FILE_EXTENSION = "sql"

BACKUP_EMAIL = "biu.music.lib@gmail.com"
FROM_EMAIL = "Music Library Backup Script<biu.music.lib@gmail.com>"
EMAIL_SUBJECT = "BIU Music Library DB Backup"
EMAIL_BODY =	"The attached file contains a backup of BIU's music library database. It is to be used only in case no data exists in" & vbCrLf & _
				"the DB and no BIU music library tables were created (I haven't tested it in a different situation). It was created" & vbCrLf & _
				"with MySQL (probably 5.x, but this may have been changed)." & vbCrLf & _
				vbCrLf & _
				"It is a zipped script with SQL commands so in order to re-create the data and the tables you should unzip it and run" & vbCrLf & _
				"a command similar to (Google ""import MySQL5"" for help, if needed):" & vbCrLf & _
				"C:\\xampp\\mysql\\bin\\mysql.exe --password="&DBPASSWORD&" --user="&DBUSER&" --host=localhost < " ' Here you should concatenate the backup file name

				
' Get last backup file, if there is one
Dim prevBackupFile
prevBackupFile = GetNewestSQLFile()

' Generate filename for backup
currentDateTime = Now
backupFilename = _
	ZeroLPad(4,Year(currentDateTime)) & "_" & ZeroLPad(2,Month(currentDateTime)) & "_" & ZeroLPad(2,Day(currentDateTime)) & "_" & _
	ZeroLPad(2,Hour(currentDateTime)) & "_" & ZeroLPad(2,Minute(currentDateTime)) & "_" & ZeroLPad(2,Second(currentDateTime)) & "_" & _
	"Music_Library_Backup." & BACKUP_FILE_EXTENSION
backupFilename = BACKUP_DIR & backupFilename

' Create backup
Dim wsShell
Set wsShell = WScript.CreateObject("WScript.Shell")
wsShell.Run MYSQLDUMP_PATH & " --password="&DBPASSWORD&" --opt --user="&DBUSER&" --host=localhost --all-databases --result-file="""&backupFilename&"""", 0, True
Wscript.Sleep 10000
Set wsShell = Nothing

' If there's a previous backup
Dim fso : Set fso = CreateObject("Scripting.FileSystemObject")
bKeepNewBackup = false
If prevBackupFile <> "" Then

	' If the previous backup is the same as the new one
	If AreSqlScriptsEqual(prevBackupFile, backupFilename) Then
	
		' Delete the new backup, it's not needed
		fso.DeleteFile(backupFilename),DeleteReadOnly
		
	' Else, the new backup is different from the previous one (the DB changed since last time)
	Else
	
		' Delete the old backup, there's a newer one
		fso.DeleteFile(prevBackupFile),DeleteReadOnly

		' Indicate we need the new backup file
		bKeepNewBackup = True
	End If

' Else, there's no previous backup
Else

	' Indicate we need the new backup file
	bKeepNewBackup = True
End If

' If we need to keep the new backup file
If bKeepNewBackup Then

	' Compress the new backup file
	compressedBackupFilename = backupFilename&".zip"
	CreateZip backupFilename&".zip", backupFilename

	' Email the compressed new backup file (we need to use '\\' instead of '\' in email body)
	EmailBackupFile _
		compressedBackupFilename, _
		FROM_EMAIL, _
		EMAIL_SUBJECT & " - " & currentDateTime, _
		BACKUP_EMAIL, _
		EMAIL_BODY & Replace(backupFilename, "\", "\\")
End If


'''''''''''''''''''''''''''''''''''''''''''''''' END OF SCRIPT ''''''''''''''''''''''''''''''''''''''''''''''''

' Pads a given number with zeroes to the left so it takes the given width. Valid input is assumed.
Function ZeroLPad(maxWidth, stringToPad)

	ZeroLPad = String(maxWidth - Len(stringToPad), "0") & stringToPad
End Function

' This procedure received a path to an attachment, and e-mail details (from, subject, destination and body) and sends the email
Sub EmailBackupFile(filePath, from, subject, destEmail, body)

	Set objMessage = CreateObject("CDO.Message")
	Set objConf = CreateObject("CDO.Configuration")

	With objMessage
		.Subject = subject 
		.From = from
		.To = destEmail
		.TextBody = body
		.AddAttachment filePath
	End With

	'==This section provides the configuration information for the remote SMTP server.

	objMessage.Configuration.Fields("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2

	' Name or IP of Remote SMTP Server
	objMessage.Configuration.Fields("http://schemas.microsoft.com/cdo/configuration/smtpserver") = "smtp.gmail.com"

	' Type of authentication, NONE, Basic (Base64 encoded), NTLM
	objMessage.Configuration.Fields("http://schemas.microsoft.com/cdo/configuration/smtpauthenticate") = 1 'cdoBasic

	' Your UserID on the SMTP server
	objMessage.Configuration.Fields("http://schemas.microsoft.com/cdo/configuration/sendusername") = "biu.music.lib@gmail.com"

	' Your password on the SMTP server
	objMessage.Configuration.Fields("http://schemas.microsoft.com/cdo/configuration/sendpassword") = "lib.music.biu"

	' Server port (typically 25)
	objMessage.Configuration.Fields("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = 465

	' Use SSL for the connection (False or True)
	objMessage.Configuration.Fields("http://schemas.microsoft.com/cdo/configuration/smtpusessl") = True

	' Connection Timeout in seconds (the maximum time CDO will try to establish a connection to the SMTP server)
	objMessage.Configuration.Fields("http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout") = 60

	objMessage.Configuration.Fields.Update

	objMessage.Send

	Set objMessage = Nothing
	Set objConf = Nothing
End Sub

' Creates a new empty ZIP file.
Sub NewZip(pathToZipFile)
 
	Dim fso
	Set fso = CreateObject("Scripting.FileSystemObject")
	Dim file
	Set file = fso.CreateTextFile(pathToZipFile)

	file.Write Chr(80) & Chr(75) & Chr(5) & Chr(6) & String(18, 0)

	file.Close
	Set fso = Nothing
	Set file = Nothing

	' Wait some time, just to make sure the file was created and closed
	WScript.Sleep 10000
End Sub

' Receives the path of a ZIP file and a path to a file for compression and
' adds that file to the ZIP archive in the given path. If a zip file exists in the
' given path it will be deleted first.
Sub CreateZip(pathToZipFile, fileToZip)

	Dim fso
	Set fso = Wscript.CreateObject("Scripting.FileSystemObject")

	pathToZipFile = fso.GetAbsolutePathName(pathToZipFile)
	fileToZip = fso.GetAbsolutePathName(fileToZip)

	' If the ZIP file already exists, delete it
	If fso.FileExists(pathToZipFile) Then
		fso.DeleteFile pathToZipFile
	End If

	' Create an empty new ZIP file
	NewZip pathToZipFile

	Dim ShellApplication
	Set ShellApplication = CreateObject("Shell.Application")

	Dim zipFile
	Set zipFile = ShellApplication.NameSpace(pathToZipFile)

	' Look at http://msdn.microsoft.com/en-us/library/bb787866(VS.85).aspx
	' for more information about the CopyHere function.
	zipFile.CopyHere fileToZip, 4

	' Sleep until there's one item in the ZIP file (meaning: the compression is done)
	Do Until zipFile.Items.Count = 1
		Wscript.Sleep(200)
	Loop
End Sub

' Returns True if the two given SQL scripts are equal and False otherwise. For the comparison, lines containing only
' whitespace or lines that contain comment marks ("--") right after the whitespace are ignored.
Function AreSqlScriptsEqual(pathToScript1, pathToScript2)

	Const ForReading = 1
	
	Dim fso
	Dim file1, file2
	Dim curLineOfFile1, curLineOfFile2
	Dim differenceFound
	Set fso = Wscript.CreateObject("Scripting.FileSystemObject")
	
	' Assume the files are equal
	differenceFound = False

	' Open both files
	Set file1 = fso.OpenTextFile(pathToScript1, ForReading)
	Set file2 = fso.OpenTextFile(pathToScript2, ForReading)

	' If no file is empty
	If (Not file1.AtEndOfStream And Not file2.AtEndOfStream) Then
	
		' Until any of the files is at the end or a difference was found
		While Not file1.AtEndOfStream And Not file2.AtEndOfStream And Not differenceFound
		
			' Get next line from first file which is not a comment nor blank
			Do

				curLineOfFile1 = TrimWhitepace(file1.ReadLine)
			Loop Until Not IsLineCommentOrBlank(curLineOfFile1) Or file1.AtEndOfStream
			
			' Get next line from second file which is not a comment nor blank
			Do

				curLineOfFile2 = TrimWhitepace(file2.ReadLine)
			Loop Until Not IsLineCommentOrBlank(curLineOfFile2) Or file2.AtEndOfStream

			' Last line might be a comment so if it is - make it an empty string
			If IsLineCommentOrBlank(curLineOfFile1) Then
				
				curLineOfFile1 = ""
			End If
			If IsLineCommentOrBlank(curLineOfFile2) Then
				
				curLineOfFile2 = ""
			End If
			
			' If the lines are different
			If (curLineOfFile1 <> curLineOfFile2) Then
			
				' Indicate a difference was found (two aligned lines in the files are different)
				differenceFound = True
			End If
		Wend
	
	' Else, at least one file is empty; If first file is empty and second file is not
	ElseIf file1.AtEndOfStream And Not file2.AtEndOfStream Then
	
		' Get next line from second file which is not a comment nor blank
		Do

			curLineOfFile2 = TrimWhitepace(file2.ReadLine)
		Loop Until Not IsLineCommentOrBlank(curLineOfFile2) Or file2.AtEndOfStream
		
		' If we got a line which is not a comment or a blank line
		If Not IsLineCommentOrBlank(curLineOfFile2) Then
		
			' Indicate a difference was found because one file is empty and the other contains a line
			' which is probably significant
			differenceFound = True
		End If
	' Else, at least one file is empty and it's not the just the first one; If only the second file is empty
	ElseIf file2.AtEndOfStream And Not file1.AtEndOfStream Then

		' Get next line from first file which is not a comment nor blank
		Do

			curLineOfFile1 = TrimWhitepace(file1.ReadLine)
		Loop Until Not IsLineCommentOrBlank(curLineOfFile1) Or file1.AtEndOfStream
		
		' If we got a line which is not a comment or a blank line
		If Not IsLineCommentOrBlank(curLineOfFile1) Then
		
			' Indicate a difference was found because one file is empty and the other contains a line
			' which is probably significant
			differenceFound = True
		End If	
	End If
	
	' Release resources
	Set file1 = Nothing
	Set file2 = Nothing
	Set fso = Nothing
	
	' Return the result of the comparison
	AreSqlScriptsEqual = Not differenceFound
End Function

' Returns True if the string is an empty string or starts with line comment marks ("--")
Function IsLineCommentOrBlank(str)

	IsLineCommentOrBlank = False
	If Left(str, 2) = "--" Or str = "" Then
	
		IsLineCommentOrBlank = True
	End If
End Function

' This function removes tabs and spaces from the beginning and ending of the given string.
' It uses a regular expression to do so.
Function TrimWhitepace(str)

	Dim regex
	Set regex = New RegExp

	' Remove all occurrences of a whitespace at the end or the beginning of the string
    regex.Pattern = "^[\s]+|[\s]+$"
    regex.Global = True
    TrimWhitepace = regex.Replace(str, "")
	
	' Release resources
	Set regex = Nothing
End Function

' This function removes tabs and spaces from the beginning and ending of the given string.
' It scans the start and end of the string until it finds a character which is not a tab or
' a space. It should do the same as TrimWhitespace and is here in case there's a problem with using
' "Regex".
Function TrimXSpace(str)

	Dim curIdx, strLen
	Dim realStrStart, realStrEnd
	Dim curChar
	strLen = Len(str)

	' Find the index of the first character which is not a whitespace
	curIdx = 1	
	continueSearch = True
	Do While curIdx <= strLen And continueSearch
	
		curChar = Mid(str,curIdx,1)
		
		If curChar = vbTab Or curChar = " " Then

			curIdx = curIdx + 1
		Else

			continueSearch = False
		End If
	Loop
	realStrStart = curIdx
	
	' Find the index of the last character which is not a whitespace from the end of the string
	curIdx = strLen
	continueSearch = True
	Do While realStrStart <= curIdx And continueSearch
	
		curChar = Mid(str,curIdx,1)
		If curChar = vbTab Or curChar = " " Then

			curIdx = curIdx - 1
		Else

			continueSearch = False
		End If
	Loop
	realStrEnd = curIdx

	' Return the string without the whitespaces of the beginning and ending
	TrimXSpace = Mid(str, realStrStart, realStrEnd - realStrStart + 1)
End Function

' This gets the newest SQL file in the backup directory.
Function GetNewestSQLFile()

	Dim aDSOrd : aDSOrd = GetStdoutOfCommand(BACKUP_DIR, "%comspec% /c dir /A:-D /B /O:D """ & BACKUP_DIR & "*." & BACKUP_FILE_EXTENSION & """")
	Dim oFile
	Dim curFileName : curFileName = ""
	
	' For each file returned (they're ordered from oldest to newest; that's how "dir" command works)
	For Each oFile In aDSOrd

		curFileName = oFile.Name
	Next
	
	' If at least one file was found
	If curFileName <> "" Then
		
		GetNewestSQLFile = BACKUP_DIR & curFileName
	Else
	
		' Indicate no file was found matching the pattern
		GetNewestSQLFile = ""
	End If
End Function

' This gets the result of the standard output after running a command.
Function GetStdoutOfCommand(sDir, sCmd)

	Dim goWS	: Set goWS		= CreateObject("WScript.Shell")
	Dim goFS	: Set goFS		= CreateObject("Scripting.FileSystemObject")
	Dim dicTmp	: Set dicTmp	= CreateObject("Scripting.Dictionary")
	Dim oExec	: Set oExec		= goWS.Exec(sCmd)

	Do Until oExec.Stdout.AtEndOfStream
		dicTmp(goFS.GetFile(goFS.BuildPath(sDir, oExec.Stdout.ReadLine()))) = Empty
	Loop
	If Not oExec.Stderr.AtEndOfStream Then
		
		'We can read the error like this--> WScript.Echo "Error: ", oExec.Stderr.ReadAll()
	End If
	
	GetStdoutOfCommand = dicTmp.Keys()
End Function
