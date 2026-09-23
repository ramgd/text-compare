<?php

/*
|--------------------------------------------------------------------------
| Tool registry
|--------------------------------------------------------------------------
|
| One source of truth for every public tool on the platform. The dashboard
| cards, the /tools directory, the main navigation, the footer, the sitemap
| and each page's SEO metadata are all generated from this file, so a tool
| added here appears everywhere without touching a template.
|
| Each entry:
|   slug        stable identifier, also the sitemap key
|   path        the route path (leading slash)
|   name        short name used on cards and in navigation
|   title       the <title> and the page's <h1> intent - unique per tool
|   category    must match a key in 'categories' below
|   icon        Font Awesome class (already loaded site-wide)
|   summary     one line, used on cards and as the meta description base
|   keywords    only used for internal search/filtering, not meta keywords
|   content     optional rich page content (see resources/views/partials/tool-content)
|
| Claims in 'content' must describe what the code actually does. Where a tool
| has real limits, they are stated rather than glossed over.
|
*/

return [

    'categories' => [
        'pdf' => [
            'name' => 'PDF Tools',
            'slug' => 'pdf',
            'icon' => 'fas fa-file-pdf',
            'blurb' => 'Merge, convert and rework PDF documents.',
        
            'description' => 'PDF is the format documents end up in when they need to look the same everywhere, which is exactly what makes it awkward to change. The tools here handle the jobs that come up most: combining several documents into one, pulling the text out into an editable Word file, and turning a Word document into a PDF for sending. Merging and PDF to Word run entirely in your browser, so those files are never uploaded. Each tool page explains what it reproduces faithfully and what it only approximates, because document conversion is never exact and it is better to know that up front.',
            'faqs' => [
                ['q' => 'Are my PDFs uploaded anywhere?', 'a' => 'Merging and PDF to Word run entirely in your browser, so those files are never transmitted. Word to PDF is the exception - it uploads the .docx, converts it in a private temporary folder, and deletes it immediately afterwards.'],
                ['q' => 'Can I work with a password-protected PDF?', 'a' => 'No. An encrypted PDF cannot be read without its password. The tool tells you which file is protected rather than failing silently, so remove the password first.'],
                ['q' => 'Will a converted document look identical to the original?', 'a' => 'No, and anyone promising that is overselling it. Text, headings, emphasis, lists and tables carry across well; exact positioning, images and embedded fonts do not. Each tool page says which is which.'],
            ],
        ],
        'text' => [
            'name' => 'Text Tools',
            'slug' => 'text',
            'icon' => 'fas fa-align-left',
            'blurb' => 'Compare, write, encode and transform text.',
        
            'description' => 'Text tools for the small jobs that interrupt real work: checking what changed between two drafts, drafting formatted notes, or escaping a value so it survives being put in a URL or a JSON payload. All of them run in your browser, so whatever you paste stays on your own machine, which matters when the text is a contract clause or a configuration file rather than a shopping list.',
            'faqs' => [
                ['q' => 'Is my text sent to your server?', 'a' => 'No. Every tool in this category runs in your browser using JavaScript.'],
                ['q' => 'Do these handle languages other than English?', 'a' => 'Yes. Text is treated as Unicode throughout, so accented characters, non-Latin scripts and emoji are handled correctly.'],
            ],
        ],
        'developer' => [
            'name' => 'Developer Tools',
            'slug' => 'developer',
            'icon' => 'fas fa-code',
            'blurb' => 'Format, inspect and generate data for development work.',
        
            'description' => 'Utilities for the moments between writing code: an API response that arrived as one unreadable line, a minified script you need to read, a checksum to verify, a query to tidy, a request to try before wiring it up. Nothing here needs an install or an account, and none of it sends your code or your credentials to us - the API tester in particular calls the endpoint straight from your browser, so keys you paste into it are never seen by this site.',
            'faqs' => [
                ['q' => 'Do you see the code or API keys I paste in?', 'a' => 'No. These tools run in your browser. The API tester sends its request directly from your browser to the endpoint you name, so it never passes through our server.'],
                ['q' => 'Why does the API tester get a CORS error?', 'a' => "Because the request comes from your browser, it follows browser security rules. An API that does not permit cross-origin requests will block it. That is the API's policy, not a fault in the tool."],
            ],
        ],
        'image' => [
            'name' => 'Image Tools',
            'slug' => 'image',
            'icon' => 'fas fa-image',
            'blurb' => 'Read text from pictures and work with colour.',
        
            'description' => 'Tools that work with pictures and colour: pulling readable text out of a photo or screenshot with OCR, turning a line of text into a shareable image, and building colour palettes with the values ready to paste into a stylesheet. The OCR runs entirely on your own device, which is unusual for a free converter and means the image never leaves your machine.',
            'faqs' => [
                ['q' => 'Is my image uploaded for OCR?', 'a' => 'No. Recognition runs on your own device. The engine downloads once, then works offline.'],
                ['q' => 'Why is the extracted text inaccurate?', 'a' => 'OCR quality depends heavily on the source. Low resolution, poor lighting, skew, unusual fonts and handwriting all reduce accuracy significantly.'],
            ],
        ],
        'utility' => [
            'name' => 'Utilities',
            'slug' => 'utility',
            'icon' => 'fas fa-toolbox',
            'blurb' => 'Everyday calculators, converters and generators.',
        
            'description' => 'Everyday calculators, converters and generators - the things you would otherwise do badly in your head or look up twice. Strong passwords, exact ages, date arithmetic that gets leap years right, unit conversion across metric and imperial, email format checking, file conversion, network lookups and outline-driven mind maps. All of them calculate in your browser.',
            'faqs' => [
                ['q' => 'Are these calculations exact?', 'a' => 'They use standard formulas and handle leap years correctly, but results are rounded for display. For anything legally or financially binding, verify independently.'],
                ['q' => 'Is anything I enter stored?', 'a' => 'No. These tools calculate in your browser. Some remember recent results in your own browser storage, which never reaches us.'],
            ],
        ],
        'game' => [
            'name' => 'Games',
            'slug' => 'game',
            'icon' => 'fas fa-gamepad',
            'blurb' => 'Small browser games to take a break with.',
        
            'description' => 'A handful of small browser games for when you want a break. They load instantly, need no account and no install, and work with touch as well as a keyboard. Each one keeps your best score in your own browser so nothing is sent anywhere.',
            'faqs' => [
                ['q' => 'Do the games work on a phone?', 'a' => 'Yes. They accept touch input as well as a keyboard, and the layouts adapt to small screens.'],
                ['q' => 'Are my scores saved to an account?', 'a' => 'There are no accounts. Best scores are kept in your own browser storage and are lost if you clear it.'],
            ],
        ],
    ],

    'tools' => [

        /* ---------------------------------------------------------- TEXT */

        [
            'slug' => 'text-compare',
            'path' => '/text-compare',
            'name' => 'Text Compare',
            'title' => 'Text Compare - Find the Differences Between Two Texts',
            'category' => 'text',
            'icon' => 'fas fa-code-compare',
            'featured' => true,
            'summary' => 'Compare two blocks of text side by side and highlight every line and word that changed.',
            'keywords' => ['diff', 'compare', 'text difference', 'file compare'],
            'content' => [
                'intro' => 'Text Compare puts two versions of the same text side by side and marks what changed between them. It aligns the two versions line by line, so unchanged lines stay level with each other even when whole blocks have been inserted or removed, then highlights the exact words that differ inside a changed line. It is useful for checking edits to a document, spotting an unintended change in a configuration file, reviewing a rewritten paragraph, or confirming that two copies of a block of text really are identical.',
                'input' => 'Two blocks of plain text, pasted or typed into the two boxes. Any length your browser can hold, in any language - Unicode, accents and emoji are compared correctly.',
                'output' => 'A two-column view. Removed text is marked in red on the left, added text in green on the right, and lines that are the same appear unmarked on both sides.',
                'steps' => [
                    'Paste the original version into the left box.',
                    'Paste the changed version into the right box.',
                    'Select Compare.',
                    'Read the highlighted result, and use the arrow controls between the two panes to jump from one difference to the next.',
                ],
                'features' => [
                    'Line-by-line alignment that re-synchronises after inserted or deleted blocks',
                    'Word-level highlighting inside a changed line',
                    'Jump controls to move between differences',
                    'Switch swaps the two sides so you can reverse the comparison',
                    'Runs entirely in your browser - the text is never uploaded',
                    'Works on a phone, with the two panes stacked',
                ],
                'example' => [
                    'caption' => 'A single word changed in the middle of a paragraph:',
                    'left_label' => 'Original',
                    'left' => "The quick brown fox\njumps over the lazy dog\nand keeps running.",
                    'right_label' => 'Modified',
                    'right' => "The quick red fox\njumps over the lazy dog\nand keeps running.",
                    'result' => 'Line 1 is marked as changed, with only "brown" highlighted in red and "red" in green. Lines 2 and 3 are shown as unchanged.',
                ],
                'faqs' => [
                    ['q' => 'Is my text uploaded anywhere?', 'a' => 'No. The comparison runs in your browser using JavaScript. Nothing you paste is sent to our server or stored.'],
                    ['q' => 'How large a text can I compare?', 'a' => 'There is no fixed limit in the tool. Very large inputs - hundreds of thousands of lines - will be slower because the work happens on your own device.'],
                    ['q' => 'Does it handle non-English text?', 'a' => 'Yes. Text is treated as Unicode, so accented characters, non-Latin scripts and emoji are compared correctly.'],
                    ['q' => 'What does the highlight actually mean?', 'a' => 'Red marks text present in the left version but not the right. Green marks text present in the right version but not the left. A line with both is a line that was rewritten.'],
                    ['q' => 'Can I compare files rather than pasted text?', 'a' => 'Not directly - this tool takes pasted or typed text. Open the file, copy its contents, and paste it into a box.'],
                    ['q' => 'Why do two lines look identical but are still marked as changed?', 'a' => 'Usually invisible differences: trailing spaces, a tab instead of spaces, or different line endings. The comparison is exact, so those count as changes.'],
                ],
            ],
        ],

        [
            'slug' => 'markdown-editor',
            'path' => '/markdown-editor',
            'name' => 'Markdown Editor',
            'title' => 'Markdown Editor with Live Preview',
            'category' => 'text',
            'icon' => 'fas fa-pen-to-square',
            'featured' => true,
            'summary' => 'Write Markdown and see the formatted result update as you type.',
            'keywords' => ['markdown', 'md', 'readme', 'preview'],
            'content' => [
                'intro' => 'A Markdown editor with a live preview beside the text. Type Markdown on one side and the rendered result appears on the other, which makes it easy to draft a README, a documentation page, release notes or a formatted comment without installing anything. A toolbar inserts the common syntax so you do not have to remember it.',
                'input' => 'Markdown text, typed or pasted.',
                'output' => 'A live HTML preview, plus optional download of the Markdown source or the generated HTML.',
                'steps' => [
                    'Type or paste Markdown into the editor pane.',
                    'Watch the preview pane update as you type.',
                    'Use the toolbar buttons to insert headings, bold, links, lists, code blocks or tables.',
                    'Copy the result, or download it as Markdown or HTML.',
                ],
                'features' => [
                    'Live preview that updates while you type',
                    'Toolbar for headings, emphasis, links, lists, code and tables',
                    'Word, character and line counts',
                    'Split, write-only and preview-only views',
                    'Starter templates for a README, a blog post and a to-do list',
                    'Runs in your browser - nothing is uploaded',
                ],
                'example' => [
                    'caption' => 'Markdown in, formatted output out:',
                    'left_label' => 'You type',
                    'left' => "# Project Title\n\nA short **bold** description.\n\n- First item\n- Second item",
                    'right_label' => 'Preview shows',
                    'right' => "Project Title  (as a large heading)\nA short bold description.\n  • First item\n  • Second item",
                    'result' => 'The preview renders headings, emphasis and lists immediately, with no build step.',
                ],
                'faqs' => [
                    ['q' => 'Which Markdown flavour is supported?', 'a' => 'Standard Markdown: headings, emphasis, links, images, lists, blockquotes, code blocks and tables.'],
                    ['q' => 'Is my document saved on your server?', 'a' => 'No. The editor runs in your browser and nothing is uploaded. Closing the tab discards the text, so download anything you want to keep.'],
                    ['q' => 'Can I export the HTML?', 'a' => 'Yes. The Export HTML button downloads the rendered output as an HTML file.'],
                    ['q' => 'Does it work on a phone?', 'a' => 'Yes. On small screens the editor and preview stack vertically and the toolbar wraps onto several rows.'],
                ],
            ],
        ],

        [
            'slug' => 'base64',
            'path' => '/base64',
            'name' => 'Base64 Encoder / Decoder',
            'title' => 'Base64 Encode and Decode - Text, Files and Images',
            'category' => 'text',
            'icon' => 'fas fa-right-left',
            'summary' => 'Convert text, files and images to Base64 and back again.',
            'keywords' => ['base64', 'encode', 'decode', 'data uri'],
            'content' => [
                'intro' => 'Base64 represents binary data using only printable characters, which is how files get embedded in JSON, email attachments, data URIs and configuration files. This tool encodes text, files and images to Base64 and decodes Base64 back to its original form.',
                'input' => 'Plain text, a file, or an image. For decoding, a Base64 string.',
                'output' => 'A Base64 string, a decoded text result, or a downloadable decoded file. Images can be produced as a data URI.',
                'steps' => [
                    'Choose the Text, File or Image tab.',
                    'Paste your text, or select a file.',
                    'Select Encode or Decode.',
                    'Copy the result, or download it.',
                ],
                'features' => [
                    'Text, file and image modes',
                    'Encoding and decoding in both directions',
                    'Data URI output for images',
                    'Copy to clipboard and download',
                    'Correct UTF-8 handling for non-English text',
                    'Runs in your browser - files are not uploaded',
                ],
                'example' => [
                    'caption' => 'Encoding a short string:',
                    'left_label' => 'Input',
                    'left' => 'hello',
                    'right_label' => 'Base64 output',
                    'right' => 'aGVsbG8=',
                    'result' => 'Decoding aGVsbG8= returns hello exactly.',
                ],
                'faqs' => [
                    ['q' => 'Is Base64 encryption?', 'a' => 'No. Base64 is an encoding, not encryption. Anyone can decode it. Never use it to protect passwords or private data.'],
                    ['q' => 'Are my files uploaded?', 'a' => 'No. Encoding and decoding happen in your browser; the file never leaves your device.'],
                    ['q' => 'Why is the Base64 bigger than the original?', 'a' => 'Base64 uses four characters for every three bytes, so the result is roughly one third larger. That is expected.'],
                    ['q' => 'Does it handle non-English characters?', 'a' => 'Yes. Text is encoded as UTF-8 first, so accented characters, non-Latin scripts and emoji round-trip correctly.'],
                ],
            ],
        ],

        [
            'slug' => 'url-encoder',
            'path' => '/url-encoder',
            'name' => 'URL Encoder / Decoder',
            'title' => 'URL Encoder and Decoder - Percent, Base64 and HTML Entities',
            'category' => 'text',
            'icon' => 'fas fa-link',
            'summary' => 'Percent-encode URLs, and encode or decode Base64 and HTML entities.',
            'keywords' => ['url encode', 'percent encoding', 'html entities', 'querystring'],
            'content' => [
                'intro' => 'URLs may only contain a limited set of characters, so spaces, ampersands, accented letters and other symbols have to be percent-encoded before they can travel safely in a link or a query string. This tool encodes and decodes URLs, and also handles Base64 and HTML entity escaping in the same place.',
                'input' => 'A URL, a query string fragment, or any text to escape.',
                'output' => 'The encoded or decoded string, ready to copy.',
                'steps' => [
                    'Pick the URL, Base64 or HTML tab.',
                    'Paste your text into the input box.',
                    'Select Encode or Decode.',
                    'Copy the result.',
                ],
                'features' => [
                    'URL percent-encoding and decoding',
                    'Base64 encoding and decoding',
                    'HTML entity escaping and unescaping',
                    'Copy and download the result',
                    'Runs in your browser',
                ],
                'example' => [
                    'caption' => 'Escaping a value for a query string:',
                    'left_label' => 'Input',
                    'left' => 'a b&c=d',
                    'right_label' => 'URL-encoded',
                    'right' => 'a%20b%26c%3Dd',
                    'result' => 'The space becomes %20, & becomes %26 and = becomes %3D, so the value cannot be mistaken for extra parameters.',
                ],
                'faqs' => [
                    ['q' => 'When do I need URL encoding?', 'a' => 'Whenever a value goes into a query string or a path and might contain spaces, &, =, ?, #, / or non-ASCII characters.'],
                    ['q' => 'What is the difference between encoding a whole URL and a single value?', 'a' => 'Encoding a whole URL preserves the separators that make it a URL. Encoding a single value escapes those separators too, which is what you want for a parameter.'],
                    ['q' => 'Is anything sent to your server?', 'a' => 'No. The conversion happens in your browser.'],
                ],
            ],
        ],

        /* ----------------------------------------------------------- PDF */

        [
            'slug' => 'pdf-toolkit',
            'path' => '/pdf-toolkit',
            'name' => 'PDF Toolkit',
            'title' => 'PDF Toolkit - Merge PDFs, PDF to Word and Word to PDF',
            'category' => 'pdf',
            'icon' => 'fas fa-file-pdf',
            'featured' => true,
            'summary' => 'Merge PDF files, convert a PDF to Word, and convert a Word document to PDF.',
            'keywords' => ['merge pdf', 'pdf to word', 'word to pdf', 'combine pdf', 'docx'],
            'content' => [
                'intro' => 'A set of PDF tools in one place. The three that are fully implemented are PDF merging, PDF to Word conversion and Word to PDF conversion; each is described below along with what it can and cannot do. Merging and PDF to Word run entirely in your browser, so those documents never leave your device. Word to PDF is the one step that needs a server, because reading a .docx faithfully requires a real Open XML parser.',
                'input' => 'PDF files for merging and for PDF to Word. A .docx file for Word to PDF.',
                'output' => 'A merged PDF, a .docx Word document, or a PDF - each downloaded directly.',
                'steps' => [
                    'Pick the tab for the job: Merge, PDF to Word, or Word to PDF.',
                    'Drag your files onto the upload area, or select them.',
                    'For merging, put the files in the order you want using the arrows beside each one.',
                    'Select the action button, then download the result.',
                ],
                'features' => [
                    'Merge any number of PDFs, in an order you control',
                    'Page size and page rotation are preserved when merging',
                    'Remove or reorder a file before merging',
                    'PDF to Word extracts the text layer into a real .docx',
                    'Word to PDF renders paragraphs, headings, bold and italic, alignment, lists and tables',
                    'Password-protected and corrupted files are reported clearly rather than failing silently',
                    'Merging and PDF to Word run in your browser; nothing is uploaded for those two',
                ],
                'sections' => [
                    [
                        'heading' => 'Merging PDFs',
                        'body' => 'Merging combines several PDFs into one document. The pages appear in the order the files are listed, so the first file supplies the first pages. Use the up and down arrows beside a file to change that order before you merge, or the cross to drop a file from the list. Each page keeps its own size and rotation, so mixing A4 portrait pages with Letter landscape pages produces a document where every page still looks the way it did in its original file. The merge runs in your browser using pdf-lib; the files are never sent anywhere.',
                    ],
                    [
                        'heading' => 'PDF to Word: what it does and what it cannot do',
                        'body' => 'This converts the text of a PDF into a real .docx file you can open and edit in Word, Google Docs or LibreOffice. It reads the PDF\'s text layer, groups the fragments back into lines and paragraphs, treats noticeably larger lines as headings, and keeps the original page order with a page break between pages. It is honest about its limits: it reproduces text, not layout. Columns, exact positioning, images, vector graphics, backgrounds and font embedding are not carried across, so the result is an editable text document rather than a pixel-for-pixel copy of the original. A scanned PDF - a document that is really a photograph of a page, with no text layer - cannot be converted this way. The tool detects that case and says so instead of handing you an empty file; for those, use the Image to Text tool, which runs OCR.',
                    ],
                    [
                        'heading' => 'Word to PDF: supported formatting',
                        'body' => 'This takes a .docx file and produces a PDF. Paragraphs, headings, bold and italic, text alignment, bulleted and numbered lists, tables and page breaks are carried across, and non-English text is handled correctly. Because the document is rendered through HTML, precise positioning, embedded fonts, floating images and complex multi-column layouts are approximated rather than reproduced exactly. Only .docx is supported. The older binary .doc format is refused with a clear message - open it in Word and save it as .docx first. This is the only tool on the site that uploads your file: it is size-limited, checked to be a genuine Word package before anything reads it, processed in a private temporary directory, and deleted immediately afterwards.',
                    ],
                    [
                        'heading' => 'Other tabs',
                        'body' => 'The Split, Compress, Protect, Convert and Edit tabs are still in development and are not yet doing real work. They are left visible because the interface is being built out, but do not rely on their output. The three tools documented above - Merge, PDF to Word and Word to PDF - are the ones that are finished and tested.',
                    ],
                ],
                'example' => [
                    'caption' => 'Merging three documents into one:',
                    'left_label' => 'Input (in this order)',
                    'left' => "report.pdf        (1 page)\nappendix.pdf      (2 pages)\ninvoice.pdf       (3 pages)",
                    'right_label' => 'Output',
                    'right' => "merged.pdf        (6 pages)",
                    'result' => 'Pages appear in list order: the report first, then the appendix, then the invoice. Each page keeps its original size and rotation.',
                ],
                'faqs' => [
                    ['q' => 'Are my files uploaded to your server?', 'a' => 'Merging and PDF to Word run entirely in your browser - those files are never uploaded. Word to PDF is the exception: the .docx is sent to our server to be converted, processed in a private temporary folder, and deleted as soon as the PDF is returned.'],
                    ['q' => 'Is there a file size limit?', 'a' => 'Merging and PDF to Word accept files up to 100 MB each. Word to PDF accepts .docx files up to 20 MB.'],
                    ['q' => 'Can I merge password-protected PDFs?', 'a' => 'No. An encrypted PDF cannot be read without its password, so the tool reports which file is protected and stops. Remove the password first, then merge.'],
                    ['q' => 'Why did my PDF to Word conversion come out empty?', 'a' => 'It was almost certainly a scanned document with no text layer. The tool detects this and tells you rather than producing a blank file - try the Image to Text tool instead.'],
                    ['q' => 'Will my Word to PDF conversion look exactly like the original?', 'a' => 'Text, headings, emphasis, alignment, lists and tables carry across well. Precise positioning, embedded fonts and complex layouts are approximated, so check the result before sending it on.'],
                    ['q' => 'Do you support .doc files?', 'a' => 'No, only .docx. The older binary .doc format is refused with a message asking you to save it as .docx first.'],
                    ['q' => 'Can I use these on a phone?', 'a' => 'Yes. The upload areas, file list and reordering arrows are all sized for touch.'],
                ],
            ],
        ],

        /* ----------------------------------------------------- DEVELOPER */

        [
            'slug' => 'hash-generator',
            'path' => '/hash-generator',
            'name' => 'Hash Generator',
            'title' => 'Hash Generator - MD5, SHA-1, SHA-256, SHA-384 and SHA-512',
            'category' => 'developer',
            'icon' => 'fas fa-fingerprint',
            'featured' => true,
            'summary' => 'Generate MD5, SHA-1, SHA-256, SHA-384 and SHA-512 hashes of text or a file.',
            'keywords' => ['md5', 'sha256', 'sha512', 'checksum', 'hash'],
            'content' => [
                'intro' => 'A hash is a short fixed-length fingerprint of some data. The same input always produces the same hash, and changing a single character produces a completely different one, which makes hashes useful for checking that a download arrived intact, that two files are identical, or that a value has not been altered. This tool generates MD5, SHA-1, SHA-256, SHA-384 and SHA-512 for text you type or for a file you select.',
                'input' => 'Any text, or any file from your device.',
                'output' => 'The hexadecimal digest for each algorithm you selected, ready to copy or download.',
                'steps' => [
                    'Choose the Text tab or the File tab.',
                    'Type or paste your text, or select a file.',
                    'Tick the algorithms you want.',
                    'Select Generate Hash, then copy the result.',
                ],
                'features' => [
                    'MD5, SHA-1, SHA-256, SHA-384 and SHA-512',
                    'Text mode and file mode',
                    'SHA algorithms use your browser\'s built-in Web Crypto implementation',
                    'Correct UTF-8 handling, so non-English text hashes correctly',
                    'Files are hashed as raw bytes, so binary files give the same digest as command-line tools',
                    'Copy a single hash or download them all',
                    'Runs in your browser - nothing is uploaded',
                ],
                'example' => [
                    'caption' => 'The standard test vectors for the string abc:',
                    'left_label' => 'Input',
                    'left' => 'abc',
                    'right_label' => 'Output',
                    'right' => "MD5      900150983cd24fb0d6963f7d28e17f72\nSHA-1    a9993e364706816aba3e25717850c26c9cd0d89d\nSHA-256  ba7816bf8f01cfea414140de5dae2223\n         b00361a396177a9cb410ff61f20015ad",
                    'result' => 'These match the published RFC 1321 and FIPS 180-4 test vectors, so you can verify the tool against a known answer.',
                ],
                'faqs' => [
                    ['q' => 'Is my text or file uploaded?', 'a' => 'No. Hashing happens in your browser. Nothing is sent to our server or stored.'],
                    ['q' => 'Which algorithm should I use?', 'a' => 'Use SHA-256 unless something specifically requires another. MD5 and SHA-1 are still fine for checksums against accidental corruption, but both are broken for security purposes and should not be used to protect anything.'],
                    ['q' => 'Can I reverse a hash back to the original text?', 'a' => 'No. Hashing only goes one way. The lookup feature only recognises a small list of very common words that were hashed in advance; it is not a way to undo a hash.'],
                    ['q' => 'Why does my file hash differ from the one on a download page?', 'a' => 'Usually the file is incomplete or was modified. Check you downloaded the whole file, and that you are comparing the same algorithm.'],
                    ['q' => 'Does it work offline?', 'a' => 'Once the page has loaded, hashing itself needs no network connection.'],
                    ['q' => 'Should I hash passwords with this?', 'a' => 'No. Passwords need a slow, salted algorithm such as bcrypt or Argon2. A plain MD5 or SHA hash is not safe for storing passwords.'],
                ],
            ],
        ],

        [
            'slug' => 'json-formatter',
            'path' => '/json_formatter',
            'name' => 'JSON Formatter',
            'title' => 'JSON Formatter and Validator - Beautify and Minify JSON',
            'category' => 'developer',
            'icon' => 'fas fa-brackets-curly',
            'featured' => true,
            'summary' => 'Format messy JSON into readable form, minify it, and catch syntax errors.',
            'keywords' => ['json', 'format', 'beautify', 'minify', 'validate'],
            'content' => [
                'intro' => 'JSON that arrives from an API is often a single unbroken line, which is hard to read and harder to debug. This tool reformats it with proper indentation, minifies it back down when you need to send it, and tells you when the JSON is not valid so you can find the mistake.',
                'input' => 'A JSON document, pasted into the editor.',
                'output' => 'The same data formatted with indentation, or minified to a single line.',
                'steps' => [
                    'Paste your JSON into the input editor.',
                    'Select Format to indent it, or Minify to compress it.',
                    'If the JSON is invalid you will be told, so you can correct it.',
                    'Copy the result from the output editor.',
                ],
                'features' => [
                    'Format with indentation, or minify to one line',
                    'Validation that reports invalid JSON instead of failing quietly',
                    'Syntax-highlighted editors',
                    'Copy the result',
                    'Runs in your browser',
                ],
                'example' => [
                    'caption' => 'The same object, before and after formatting:',
                    'left_label' => 'Input',
                    'left' => '{"name":"Ada","langs":["php","js"],"active":true}',
                    'right_label' => 'Formatted',
                    'right' => "{\n    \"name\": \"Ada\",\n    \"langs\": [\n        \"php\",\n        \"js\"\n    ],\n    \"active\": true\n}",
                    'result' => 'Minifying the formatted version returns it to the single line it started as.',
                ],
                'faqs' => [
                    ['q' => 'Is my JSON sent anywhere?', 'a' => 'No. Formatting and validation happen in your browser.'],
                    ['q' => 'Why does it say my JSON is invalid?', 'a' => 'The usual causes are a trailing comma, single quotes instead of double quotes, an unquoted key, or a missing bracket. JSON is stricter than JavaScript object syntax.'],
                    ['q' => 'Does formatting change my data?', 'a' => 'No. Only whitespace changes. The values, and the order of keys, stay as they were.'],
                ],
            ],
        ],

        [
            'slug' => 'code-beautifier',
            'path' => '/code-beautifier',
            'name' => 'Code Beautifier',
            'title' => 'Code Beautifier and Minifier - JavaScript, HTML, CSS, SQL',
            'category' => 'developer',
            'icon' => 'fas fa-wand-magic-sparkles',
            'summary' => 'Reformat JavaScript, HTML, CSS, JSON and SQL into readable code, or minify it.',
            'keywords' => ['beautify', 'format', 'minify', 'javascript', 'css', 'sql'],
            'content' => [
                'intro' => 'Reformats code that has been minified, badly indented or pasted out of a log into something readable, and minifies it again when you need it small. It handles JavaScript, HTML, CSS, JSON and SQL, and detects the language automatically.',
                'input' => 'A block of source code in a supported language.',
                'output' => 'The same code, reindented or minified.',
                'steps' => [
                    'Paste your code into the input editor.',
                    'Pick the language, or let it be detected.',
                    'Select Beautify or Minify.',
                    'Copy the result.',
                ],
                'features' => [
                    'JavaScript, HTML, CSS, JSON and SQL',
                    'Automatic language detection',
                    'Beautify and minify in both directions',
                    'Syntax-highlighted editors',
                    'Runs in your browser',
                ],
                'example' => [
                    'caption' => 'Minified JavaScript, made readable:',
                    'left_label' => 'Input',
                    'left' => 'function a(){return 1}',
                    'right_label' => 'Beautified',
                    'right' => "function a() {\n    return 1\n}",
                    'result' => 'Indentation and line breaks are restored without changing what the code does.',
                ],
                'faqs' => [
                    ['q' => 'Does beautifying change how my code behaves?', 'a' => 'No. Only formatting changes. Minifying removes whitespace and comments, which also does not change behaviour, though it makes the code harder to read.'],
                    ['q' => 'Is my code uploaded?', 'a' => 'No. Everything happens in your browser.'],
                    ['q' => 'Which languages are supported?', 'a' => 'JavaScript, HTML, CSS, JSON and SQL.'],
                ],
            ],
        ],

        [
            'slug' => 'sql-minifier',
            'path' => '/sql_minifier',
            'name' => 'SQL Minifier',
            'title' => 'SQL Minifier and Formatter',
            'category' => 'developer',
            'icon' => 'fas fa-database',
            'summary' => 'Strip comments and extra whitespace from SQL, or format it for readability.',
            'keywords' => ['sql', 'minify', 'format', 'query'],
            'content' => [
                'intro' => 'Collapses a SQL statement onto one line by removing comments and redundant whitespace, which is handy when a query has to go into a single-line config value, a log entry or a code string. It can also format a query back out for reading.',
                'input' => 'A SQL query or script.',
                'output' => 'The minified or formatted query.',
                'steps' => [
                    'Paste your SQL into the input box.',
                    'Select Minify to compress it, or Format to lay it out.',
                    'Copy the result.',
                ],
                'features' => [
                    'Removes single-line and block comments',
                    'Collapses repeated whitespace',
                    'Formatting for readability',
                    'Runs in your browser',
                ],
                'example' => [
                    'caption' => 'A commented query, minified:',
                    'left_label' => 'Input',
                    'left' => "SELECT  *   FROM users -- all rows\nWHERE active = 1",
                    'right_label' => 'Minified',
                    'right' => 'SELECT * FROM users WHERE active = 1',
                    'result' => 'The comment is removed and repeated spaces collapse to one.',
                ],
                'faqs' => [
                    ['q' => 'Does it validate my SQL?', 'a' => 'No. It reformats text; it does not connect to a database or check that the query is correct.'],
                    ['q' => 'Is my query uploaded?', 'a' => 'No. It is processed in your browser.'],
                    ['q' => 'Will it break string literals containing "--"?', 'a' => 'Comment removal is based on pattern matching, so an unusual query with comment-like text inside a string may need checking. Review the output before running it.'],
                ],
            ],
        ],

        [
            'slug' => 'api-tester',
            'path' => '/api_tester',
            'name' => 'API Tester',
            'title' => 'Online API Tester - Send HTTP Requests From Your Browser',
            'category' => 'developer',
            'icon' => 'fas fa-plug',
            'summary' => 'Send GET, POST, PUT and DELETE requests and inspect the response.',
            'keywords' => ['api', 'rest', 'http', 'request', 'postman'],
            'content' => [
                'intro' => 'Send an HTTP request to an API and look at what comes back, without installing a desktop client. Set the method, the URL, query parameters, headers and a request body, then read the response.',
                'input' => 'A request URL, an HTTP method, and optionally headers, query parameters and a body.',
                'output' => 'The response body, shown formatted, along with the status.',
                'steps' => [
                    'Choose the HTTP method and enter the request URL.',
                    'Add query parameters or headers if the endpoint needs them.',
                    'Add a request body for POST or PUT.',
                    'Select Send and read the response.',
                ],
                'features' => [
                    'GET, POST, PUT and DELETE',
                    'Custom headers and query parameters',
                    'Request body editor',
                    'Formatted response view',
                    'The request goes directly from your browser to the API',
                ],
                'example' => [
                    'caption' => 'A simple GET request:',
                    'left_label' => 'Request',
                    'left' => "GET  https://api.example.com/v1/status\nAccept: application/json",
                    'right_label' => 'Response',
                    'right' => "{\n    \"status\": \"ok\"\n}",
                    'result' => 'The response body is displayed formatted so it is easy to read.',
                ],
                'faqs' => [
                    ['q' => 'Why does my request fail with a CORS error?', 'a' => 'The request is made by your browser, so it follows browser security rules. An API that does not send permissive CORS headers will block a request from another origin. That is the API\'s policy, not a fault in this tool.'],
                    ['q' => 'Do you see or store my API keys?', 'a' => 'No. The request goes straight from your browser to the API you name. It does not pass through our server, and nothing is stored.'],
                    ['q' => 'Can I call an endpoint on localhost?', 'a' => 'Only if that endpoint is reachable from your own browser and allows the request.'],
                ],
            ],
        ],

        [
            'slug' => 'qr-generator',
            'path' => '/qr-generator',
            'name' => 'QR Code Generator',
            'title' => 'QR Code Generator - Create and Download QR Codes',
            'category' => 'developer',
            'icon' => 'fas fa-qrcode',
            'summary' => 'Turn a link or a piece of text into a downloadable QR code.',
            'keywords' => ['qr', 'qr code', 'barcode', 'generator'],
            'content' => [
                'intro' => 'Creates a QR code from a URL or any short piece of text, which you can then download and use on a poster, a business card, a label or a slide. The appearance of the code can be adjusted while keeping it scannable.',
                'input' => 'A URL or a short piece of text.',
                'output' => 'A QR code image you can download.',
                'steps' => [
                    'Enter the URL or text to encode.',
                    'Adjust the appearance if you want to.',
                    'Select Generate.',
                    'Download the image.',
                ],
                'features' => [
                    'Encodes URLs and plain text',
                    'Adjustable appearance',
                    'Downloadable image',
                    'Preview before downloading',
                ],
                'example' => [
                    'caption' => 'Encoding a link:',
                    'left_label' => 'Input',
                    'left' => 'https://example.com/menu',
                    'right_label' => 'Output',
                    'right' => 'A square QR image that opens that link when scanned with a phone camera.',
                    'result' => 'Test the downloaded image with a phone before printing it.',
                ],
                'faqs' => [
                    ['q' => 'Do QR codes expire?', 'a' => 'No. The code encodes your text directly, so it keeps working. If it points to a URL, it stops being useful only when that URL stops working.'],
                    ['q' => 'How much text can a QR code hold?', 'a' => 'Technically a few thousand characters, but the more you encode the denser the code becomes and the harder it is to scan. Short URLs work best.'],
                    ['q' => 'Can I use the code commercially?', 'a' => 'Yes. QR is an open standard and the codes you generate are yours to use.'],
                ],
            ],
        ],

        /* --------------------------------------------------------- IMAGE */

        [
            'slug' => 'image-to-text',
            'path' => '/image-to-text',
            'name' => 'Image to Text (OCR)',
            'title' => 'Image to Text - Free Online OCR',
            'category' => 'image',
            'icon' => 'fas fa-file-lines',
            'featured' => true,
            'summary' => 'Extract readable text from a picture or a screenshot using OCR.',
            'keywords' => ['ocr', 'image to text', 'extract text', 'scan'],
            'content' => [
                'intro' => 'Optical character recognition reads the words in a picture and gives you back editable text. This is what you need for a photographed page, a screenshot you cannot select text in, or a scanned document. Recognition runs in your browser, so the image is never uploaded.',
                'input' => 'An image: a photo, a screenshot or a scan.',
                'output' => 'The recognised text, which you can copy or download.',
                'steps' => [
                    'Drag an image onto the upload area, or select one.',
                    'Select Extract Text and wait while recognition runs.',
                    'Review the text - OCR is not perfect and may need correcting.',
                    'Copy or download the result.',
                ],
                'features' => [
                    'Recognition runs in your browser; the image is not uploaded',
                    'Works with photos, screenshots and scans',
                    'Progress shown while recognition runs',
                    'Copy or download the extracted text',
                ],
                'example' => [
                    'caption' => 'A photographed sign:',
                    'left_label' => 'Input',
                    'left' => 'A photo of a notice board with printed text',
                    'right_label' => 'Output',
                    'right' => 'The printed wording as editable text you can copy',
                    'result' => 'Clear, straight, well-lit printed text gives the best results. Handwriting and skewed photos are much less reliable.',
                ],
                'faqs' => [
                    ['q' => 'Is my image uploaded?', 'a' => 'No. Recognition runs in your browser. The image never leaves your device.'],
                    ['q' => 'Why is the result inaccurate?', 'a' => 'OCR quality depends heavily on the image. Low resolution, poor lighting, a skewed angle, unusual fonts or handwriting all reduce accuracy. A straight, sharp, well-lit image of printed text works best.'],
                    ['q' => 'Why does it take a while?', 'a' => 'The recognition engine runs on your own device and has to download once before first use. Large images take longer.'],
                    ['q' => 'Can it read a PDF?', 'a' => 'This tool takes images. For a PDF that already contains real text, use PDF to Word in the PDF Toolkit instead.'],
                ],
            ],
        ],

        [
            'slug' => 'text-to-image',
            'path' => '/text-to-image',
            'name' => 'Text to Image',
            'title' => 'Text to Image - Turn Words Into a Downloadable Picture',
            'category' => 'image',
            'icon' => 'fas fa-font',
            'summary' => 'Render text as a styled image you can download and share.',
            'keywords' => ['text to image', 'quote image', 'social media', 'banner'],
            'content' => [
                'intro' => 'Renders a piece of text as an image - a quote card, a social post, a simple banner or a thumbnail. You control the font, size, colours, background and alignment, then download the result as a picture.',
                'input' => 'The text you want to render, plus styling choices.',
                'output' => 'A downloadable image.',
                'steps' => [
                    'Type or paste your text.',
                    'Choose the font, size, colours and background.',
                    'Check the live preview.',
                    'Download the image.',
                ],
                'features' => [
                    'Font, size and colour control',
                    'Background colour or gradient',
                    'Bold, italic, shadow and outline options',
                    'Live preview',
                    'Rendered in your browser and downloaded directly',
                ],
                'example' => [
                    'caption' => 'A quote card:',
                    'left_label' => 'Input',
                    'left' => 'Ship it, then improve it.',
                    'right_label' => 'Output',
                    'right' => 'A PNG with that sentence centred in your chosen font over your chosen background.',
                    'result' => 'Ready to post or drop into a slide.',
                ],
                'faqs' => [
                    ['q' => 'What image format do I get?', 'a' => 'A PNG, which keeps text edges crisp.'],
                    ['q' => 'Can I use the image commercially?', 'a' => 'The image is generated from your own text, so it is yours. Check the licence of any font you choose if that matters for your use.'],
                    ['q' => 'Is anything uploaded?', 'a' => 'No. The image is drawn in your browser.'],
                ],
            ],
        ],

        [
            'slug' => 'color-palette',
            'path' => '/color-palette',
            'name' => 'Colour Palette Generator',
            'title' => 'Colour Palette Generator - Build and Copy Colour Schemes',
            'category' => 'image',
            'icon' => 'fas fa-palette',
            'summary' => 'Generate colour palettes and copy values as HEX, RGB or HSL.',
            'keywords' => ['color palette', 'hex', 'rgb', 'hsl', 'design'],
            'content' => [
                'intro' => 'Builds colour palettes for a design and gives you the values in the formats you actually need. Pick a base colour and generate a harmonious scheme, then copy any colour as HEX, RGB or HSL straight into your stylesheet or design file.',
                'input' => 'A base colour, or a generated random palette.',
                'output' => 'A set of colours with their HEX, RGB and HSL values.',
                'steps' => [
                    'Choose a base colour, or generate a palette.',
                    'Browse the generated colours.',
                    'Select a colour to see its details.',
                    'Copy the value in the format you need.',
                ],
                'features' => [
                    'Palette generation from a base colour',
                    'HEX, RGB and HSL values for every colour',
                    'Copy a single value with one tap',
                    'Recently used palettes kept in your browser',
                ],
                'example' => [
                    'caption' => 'One colour, three notations:',
                    'left_label' => 'Colour',
                    'left' => 'The platform orange',
                    'right_label' => 'Values',
                    'right' => "HEX  #ff7a18\nRGB  rgb(255, 122, 24)\nHSL  hsl(25, 100%, 55%)",
                    'result' => 'Copy whichever notation your stylesheet or design tool expects.',
                ],
                'faqs' => [
                    ['q' => 'What is the difference between HEX, RGB and HSL?', 'a' => 'They describe the same colour differently. HEX and RGB give red, green and blue amounts; HSL gives hue, saturation and lightness, which makes it easier to create a lighter or darker version of a colour by hand.'],
                    ['q' => 'Are my palettes saved to an account?', 'a' => 'No. There are no accounts. Recent palettes are kept in your own browser storage and never leave your device.'],
                ],
            ],
        ],

        /* ------------------------------------------------------ UTILITY */

        [
            'slug' => 'password-generator',
            'path' => '/password-generator',
            'name' => 'Password Generator',
            'title' => 'Strong Random Password Generator',
            'category' => 'utility',
            'icon' => 'fas fa-key',
            'featured' => true,
            'summary' => 'Create strong random passwords with the length and character types you choose.',
            'keywords' => ['password', 'generator', 'random', 'secure'],
            'content' => [
                'intro' => 'Generates a random password of the length you choose, from the character types you select. Random passwords are much harder to guess than anything memorable, which is why they are worth using together with a password manager.',
                'input' => 'A length, and which character types to include.',
                'output' => 'A generated password you can copy.',
                'steps' => [
                    'Set the length with the slider.',
                    'Tick the character types: uppercase, lowercase, numbers, symbols.',
                    'Select Generate Password.',
                    'Copy the result straight into your password manager.',
                ],
                'features' => [
                    'Adjustable length',
                    'Uppercase, lowercase, numbers and symbols',
                    'Copy to clipboard',
                    'Generated in your browser - the password is never transmitted',
                ],
                'example' => [
                    'caption' => 'A 16-character password with all types enabled:',
                    'left_label' => 'Settings',
                    'left' => "Length: 16\nUppercase, lowercase, numbers, symbols",
                    'right_label' => 'Result',
                    'right' => 'A 16-character mix, different every time you press Generate.',
                    'result' => 'Longer is better. 16 characters or more is a sensible minimum for an important account.',
                ],
                'faqs' => [
                    ['q' => 'Is the generated password sent anywhere?', 'a' => 'No. It is generated in your browser and never transmitted or stored by us.'],
                    ['q' => 'How long should a password be?', 'a' => 'Length matters more than complexity. Aim for at least 16 characters for anything important.'],
                    ['q' => 'Should I reuse a generated password?', 'a' => 'No. Use a different password for every account, and a password manager to keep track of them.'],
                    ['q' => 'Is this random enough?', 'a' => 'The password is generated in your browser for one-off use. For high-security needs, prefer the generator built into a reputable password manager.'],
                ],
            ],
        ],

        [
            'slug' => 'age-calculator',
            'path' => '/age-calculator',
            'name' => 'Age Calculator',
            'title' => 'Age Calculator - Exact Age in Years, Months and Days',
            'category' => 'utility',
            'icon' => 'fas fa-cake-candles',
            'summary' => 'Work out an exact age from a date of birth, down to days and hours.',
            'keywords' => ['age', 'birthday', 'date of birth', 'calculator'],
            'content' => [
                'intro' => 'Calculates an exact age from a date of birth: years, months and days, plus totals in weeks, days, hours and minutes, and how long until the next birthday. Useful for forms that need an exact age, for eligibility checks, or out of curiosity.',
                'input' => 'A date of birth, and optionally a time.',
                'output' => 'The exact age broken down several ways, and the next birthday.',
                'steps' => [
                    'Enter the date of birth.',
                    'Add a time of day if you want the hour-level detail.',
                    'Select Calculate.',
                    'Read the breakdown.',
                ],
                'features' => [
                    'Years, months and days',
                    'Totals in weeks, days, hours and minutes',
                    'Countdown to the next birthday',
                    'Leap years handled correctly',
                    'Calculated in your browser',
                ],
                'example' => [
                    'caption' => 'A date of birth of 1 January 1990:',
                    'left_label' => 'Input',
                    'left' => 'Date of birth: 1990-01-01',
                    'right_label' => 'Output',
                    'right' => "Age in years, months and days\nTotal days lived\nDays until the next birthday",
                    'result' => 'The result is calculated against today\'s date on your own device.',
                ],
                'faqs' => [
                    ['q' => 'Which timezone is used?', 'a' => 'Your device\'s own date and time, so the result matches the calendar you are looking at.'],
                    ['q' => 'Are leap years handled?', 'a' => 'Yes. February 29 birthdays and leap years are accounted for.'],
                    ['q' => 'Is my date of birth stored?', 'a' => 'No. The calculation happens in your browser and nothing is sent to us.'],
                ],
            ],
        ],

        [
            'slug' => 'date-calculator',
            'path' => '/date-calculator',
            'name' => 'Date Calculator',
            'title' => 'Date Calculator - Difference Between Dates and Date Maths',
            'category' => 'utility',
            'icon' => 'fas fa-calendar-days',
            'summary' => 'Find the gap between two dates, or add and subtract days from a date.',
            'keywords' => ['date difference', 'add days', 'working days', 'calculator'],
            'content' => [
                'intro' => 'Two related jobs in one place: measuring the gap between two dates, and moving a date forwards or backwards by a number of days, weeks, months or years. Handy for deadlines, notice periods, project planning and anything where counting on a calendar by hand invites mistakes.',
                'input' => 'Two dates, or one date plus an amount to add or subtract.',
                'output' => 'The difference expressed several ways, or the resulting date.',
                'steps' => [
                    'Pick the tab for the calculation you want.',
                    'Enter the dates, or the date and the amount to shift by.',
                    'Select Calculate.',
                    'Read the result.',
                ],
                'features' => [
                    'Difference between two dates',
                    'Add or subtract days, weeks, months and years',
                    'Results in several units',
                    'Leap years handled correctly',
                    'Calculated in your browser',
                ],
                'example' => [
                    'caption' => 'Measuring a gap:',
                    'left_label' => 'Input',
                    'left' => "From: 2026-01-01\nTo:   2026-03-15",
                    'right_label' => 'Output',
                    'right' => "73 days\n2 months and 14 days",
                    'result' => 'The same gap is shown in more than one unit so you can use whichever fits.',
                ],
                'faqs' => [
                    ['q' => 'Does it count both the start and end date?', 'a' => 'The difference is the number of days between the two dates, so a gap from the 1st to the 2nd is one day.'],
                    ['q' => 'Are leap years handled?', 'a' => 'Yes.'],
                    ['q' => 'Is anything uploaded?', 'a' => 'No. It is calculated in your browser.'],
                ],
            ],
        ],

        [
            'slug' => 'unit-converter',
            'path' => '/unit-converter',
            'name' => 'Unit Converter',
            'title' => 'Unit Converter - Length, Weight, Temperature and More',
            'category' => 'utility',
            'icon' => 'fas fa-ruler-combined',
            'summary' => 'Convert between metric and imperial units across many categories.',
            'keywords' => ['unit converter', 'metric', 'imperial', 'temperature'],
            'content' => [
                'intro' => 'Converts between units across a range of categories - length, weight, temperature, area, volume, speed, time and digital storage. Useful when a recipe, a specification or a supplier uses a different system from the one you think in.',
                'input' => 'A value, the unit it is in, and the unit you want.',
                'output' => 'The converted value.',
                'steps' => [
                    'Choose a category.',
                    'Enter the value and pick the unit it is in.',
                    'Pick the unit to convert to.',
                    'Read the converted result.',
                ],
                'features' => [
                    'Many categories including length, weight, temperature and storage',
                    'Metric and imperial units',
                    'Swap the two units with one tap',
                    'Recent conversions kept in your browser',
                    'Calculated in your browser',
                ],
                'example' => [
                    'caption' => 'A common kitchen conversion:',
                    'left_label' => 'Input',
                    'left' => '180 °C',
                    'right_label' => 'Output',
                    'right' => '356 °F',
                    'result' => 'Temperature uses an offset as well as a scale factor, which is why it cannot be done by simple multiplication.',
                ],
                'faqs' => [
                    ['q' => 'How precise are the results?', 'a' => 'Conversions use standard factors and are rounded for display. For engineering or laboratory work, check the rounding against your own requirements.'],
                    ['q' => 'Why is temperature different from other conversions?', 'a' => 'Celsius and Fahrenheit have different zero points, so converting needs an offset as well as a ratio.'],
                    ['q' => 'Is anything uploaded?', 'a' => 'No.'],
                ],
            ],
        ],

        [
            'slug' => 'email-validator',
            'path' => '/email-validator',
            'name' => 'Email Validator',
            'title' => 'Email Address Validator - Check Format and Structure',
            'category' => 'utility',
            'icon' => 'fas fa-envelope-circle-check',
            'summary' => 'Check whether email addresses are correctly formed, one at a time or in bulk.',
            'keywords' => ['email', 'validate', 'verify', 'bulk'],
            'content' => [
                'intro' => 'Checks that an email address is correctly formed - that it has a sensible local part, a single @, and a plausible domain. This catches typos and malformed entries in a list before you try to use them. It checks structure, not whether a mailbox actually exists.',
                'input' => 'A single email address, or a list for bulk checking.',
                'output' => 'A valid or invalid verdict for each address, with a reason.',
                'steps' => [
                    'Enter one address, or paste a list into the bulk tab.',
                    'Select Validate.',
                    'Review which addresses passed and which did not.',
                ],
                'features' => [
                    'Single and bulk checking',
                    'Format and structure validation',
                    'Common typo detection',
                    'Results you can copy or download',
                    'Runs in your browser',
                ],
                'example' => [
                    'caption' => 'Two addresses, one malformed:',
                    'left_label' => 'Input',
                    'left' => "ada@example.com\nada@@example",
                    'right_label' => 'Output',
                    'right' => "ada@example.com   valid format\nada@@example      invalid - two @ symbols, no top-level domain",
                    'result' => 'Format checking finds typos; it does not prove the first mailbox is real.',
                ],
                'faqs' => [
                    ['q' => 'Does this prove the address exists?', 'a' => 'No. It checks the format only. Confirming that a mailbox can receive mail requires actually sending to it, which this tool does not do.'],
                    ['q' => 'Is my list uploaded?', 'a' => 'No. Validation runs in your browser, so the addresses stay on your device.'],
                    ['q' => 'Why was a valid-looking address rejected?', 'a' => 'Some unusual but technically legal addresses are rejected by strict checks. If you believe an address is correct, trust the address.'],
                ],
            ],
        ],

        [
            'slug' => 'file-converter',
            'path' => '/file-converter',
            'name' => 'File Converter',
            'title' => 'File Format Converter - Text and Data Formats',
            'category' => 'utility',
            'icon' => 'fas fa-file-arrow-down',
            'summary' => 'Convert between common text and data file formats in your browser.',
            'keywords' => ['convert', 'csv', 'json', 'file format'],
            'content' => [
                'intro' => 'Converts between common text and data formats so you can move information between tools that expect different file types. Everything is processed in your browser.',
                'input' => 'A file in one of the supported formats.',
                'output' => 'The same data in the format you chose, ready to download.',
                'steps' => [
                    'Select or drag in your file.',
                    'Choose the format to convert to.',
                    'Select Convert.',
                    'Download the result.',
                ],
                'features' => [
                    'Common text and data formats',
                    'Drag and drop',
                    'Download the converted file',
                    'Runs in your browser - files are not uploaded',
                ],
                'example' => [
                    'caption' => 'Moving tabular data between formats:',
                    'left_label' => 'Input',
                    'left' => 'data.csv',
                    'right_label' => 'Output',
                    'right' => 'data.json',
                    'result' => 'Each row becomes an object keyed by the column headings.',
                ],
                'faqs' => [
                    ['q' => 'Are my files uploaded?', 'a' => 'No. Conversion runs in your browser.'],
                    ['q' => 'Which formats are supported?', 'a' => 'Common text and data formats. The format selector on the page lists what is currently available.'],
                    ['q' => 'Can it convert Word or PDF files?', 'a' => 'No - use the PDF Toolkit for PDF and Word conversions.'],
                ],
            ],
        ],

        [
            'slug' => 'ip-tools',
            'path' => '/ip-tools',
            'name' => 'IP Tools',
            'title' => 'IP Address Tools - Lookup and Network Utilities',
            'category' => 'utility',
            'icon' => 'fas fa-network-wired',
            'summary' => 'Look up IP address details and work through common network checks.',
            'keywords' => ['ip', 'network', 'lookup', 'subnet'],
            'content' => [
                'intro' => 'A set of IP address utilities for everyday network questions: seeing your own public address, looking up details for an address, and working through common network calculations.',
                'input' => 'An IP address, or nothing at all to see your own.',
                'output' => 'Details about the address.',
                'steps' => [
                    'Open the tab for the check you want.',
                    'Enter an address, or use your own.',
                    'Read the result.',
                ],
                'features' => [
                    'Your own public IP address',
                    'Lookup for a given address',
                    'Network calculation helpers',
                    'Copyable results',
                ],
                'example' => [
                    'caption' => 'Checking your own address:',
                    'left_label' => 'Input',
                    'left' => '(none - the tool detects it)',
                    'right_label' => 'Output',
                    'right' => 'Your public IPv4 or IPv6 address as seen from the internet',
                    'result' => 'This is the address a website sees, which is usually your router\'s address rather than your device\'s local one.',
                ],
                'faqs' => [
                    ['q' => 'Why is my IP different from the one in my computer settings?', 'a' => 'Your device has a private address on your local network. Websites see your router\'s public address instead.'],
                    ['q' => 'Do you log the addresses I look up?', 'a' => 'We do not store lookups. Some lookups require calling a third-party service, which will see the request.'],
                    ['q' => 'Does an IP address give a precise location?', 'a' => 'No. IP-based location is approximate - often only the city or region, and sometimes wrong, particularly on mobile networks or a VPN.'],
                ],
            ],
        ],

        [
            'slug' => 'mind-map',
            'path' => '/mind-map',
            'name' => 'Mind Map Generator',
            'title' => 'Mind Map Generator - Turn an Outline Into a Diagram',
            'category' => 'utility',
            'icon' => 'fas fa-diagram-project',
            'summary' => 'Write an indented outline and see it as a mind map you can export.',
            'keywords' => ['mind map', 'outline', 'diagram', 'brainstorm'],
            'content' => [
                'intro' => 'Turns a simple indented outline into a mind map diagram. Typing an outline is much faster than dragging boxes around, so this suits brainstorming, planning and structuring notes.',
                'input' => 'An indented text outline, where indentation sets the hierarchy.',
                'output' => 'A mind map diagram you can view and export.',
                'steps' => [
                    'Type your outline, using indentation for sub-topics.',
                    'Watch the diagram build as you type.',
                    'Adjust the layout or styling.',
                    'Export the diagram.',
                ],
                'features' => [
                    'Outline-driven diagram building',
                    'Live update as you type',
                    'Layout and styling options',
                    'Export the result',
                    'Runs in your browser',
                ],
                'example' => [
                    'caption' => 'Indentation becomes hierarchy:',
                    'left_label' => 'Outline',
                    'left' => "Launch\n  Website\n    Copy\n    Design\n  Marketing\n    Email",
                    'right_label' => 'Diagram',
                    'right' => 'Launch at the centre, with Website and Marketing branching off it, and their own children beyond.',
                    'result' => 'Each level of indentation becomes another level of the map.',
                ],
                'faqs' => [
                    ['q' => 'Is my outline saved?', 'a' => 'It stays in your browser. Nothing is uploaded, so export anything you want to keep.'],
                    ['q' => 'How deep can the hierarchy go?', 'a' => 'Several levels work well. Very deep outlines become hard to read as a diagram.'],
                ],
            ],
        ],

        [
            'slug' => 'speed-test',
            'path' => '/speed-test',
            'name' => 'Internet Speed Test',
            'title' => 'Internet Speed Test - Check Your Connection Speed',
            'category' => 'utility',
            'icon' => 'fas fa-gauge-high',
            'summary' => 'Measure your connection speed from the browser.',
            'keywords' => ['speed test', 'bandwidth', 'internet speed'],
            'content' => [
                'intro' => 'Measures your connection speed from the browser and shows the result. Useful for a quick check when a connection feels slower than it should.',
                'input' => 'None - just start the test.',
                'output' => 'A measured speed reading.',
                'steps' => [
                    'Select Start.',
                    'Wait while the test runs.',
                    'Read the result.',
                ],
                'features' => [
                    'Runs in the browser with no installation',
                    'Clear on-screen result',
                ],
                'example' => [
                    'caption' => 'A typical run:',
                    'left_label' => 'Action',
                    'left' => 'Start the test and wait',
                    'right_label' => 'Result',
                    'right' => 'A speed figure for your current connection',
                    'result' => 'Run it a few times - a single reading can be affected by whatever else is using the connection.',
                ],
                'faqs' => [
                    ['q' => 'Why is the result lower than the speed I pay for?', 'a' => 'Advertised speeds are maximums. Wi-Fi, distance from the router, other devices, the time of day and the browser itself all reduce what a test measures.'],
                    ['q' => 'Is a browser test as accurate as a dedicated app?', 'a' => 'It is an indication rather than a precise measurement. For a definitive figure use a wired connection and a dedicated testing service.'],
                ],
            ],
        ],

        [
            'slug' => 'speed-checker',
            'path' => '/speed-checker',
            'name' => 'Speed Checker',
            'title' => 'Connection Speed Checker with Charted History',
            'category' => 'utility',
            'icon' => 'fas fa-chart-line',
            'summary' => 'Run connection checks and see the readings plotted over time.',
            'keywords' => ['speed', 'latency', 'connection', 'chart'],
            'content' => [
                'intro' => 'Runs connection checks and plots the readings on a chart, which makes it easier to see whether a connection is consistently slow or just dipped once.',
                'input' => 'None - just start a check.',
                'output' => 'Readings shown on a chart.',
                'steps' => [
                    'Select Start.',
                    'Let it take several readings.',
                    'Read the chart to see the pattern.',
                ],
                'features' => [
                    'Repeated readings',
                    'Results plotted on a chart',
                    'Runs in the browser',
                ],
                'example' => [
                    'caption' => 'Spotting an intermittent problem:',
                    'left_label' => 'Action',
                    'left' => 'Run several checks in a row',
                    'right_label' => 'Result',
                    'right' => 'A line chart where an occasional dip stands out from the general level',
                    'result' => 'A pattern over several readings tells you more than any single number.',
                ],
                'faqs' => [
                    ['q' => 'How many readings should I take?', 'a' => 'Enough to see a pattern - a handful over a few minutes is usually enough to tell a steady connection from an erratic one.'],
                    ['q' => 'Is the data stored on your server?', 'a' => 'No. The readings stay in your browser for the session.'],
                ],
            ],
        ],

        /* ----------------------------------------------------------- GAMES */

        [
            'slug' => 'memory-game',
            'path' => '/memory-game',
            'name' => 'Memory Card Match',
            'title' => 'Memory Card Match - Free Browser Game',
            'category' => 'game',
            'icon' => 'fas fa-clone',
            'summary' => 'Find the matching pairs in as few moves as you can.',
            'keywords' => ['memory game', 'card match', 'concentration'],
            'content' => [
                'intro' => 'Memory Card Match is the classic concentration game. A grid of cards sits face down; turn two at a time and try to remember where each picture was so you can pair them up. It is a genuine short-term memory exercise, and the fewer moves you finish in, the better you did.',
                'input' => 'Nothing to upload. Choose a difficulty and start playing.',
                'output' => 'A completed grid, with your move count, elapsed time and score, saved as a personal best in your own browser.',
                'steps' => [
                    'Pick a difficulty - a larger grid means more pairs to track.',
                    'Select a card to turn it face up, then select a second one.',
                    'If the two match they stay up; if not, both turn back over and it is your turn again.',
                    'Clear the whole grid, then try to beat your own move count.',
                ],
                'features' => [
                    'Several grid sizes',
                    'Move counter and timer',
                    'Best score kept in your browser',
                    'Works with touch and with a mouse',
                    'Runs entirely offline once loaded',
                ],
                'sections' => [
                    ['heading' => 'Tips', 'body' => 'Work a row at a time rather than turning cards at random. Most people find it easier to remember a position relative to a corner or an edge than somewhere in the middle of the grid.'],
                ],
                'faqs' => [
                    ['q' => 'Are my scores saved to an account?', 'a' => 'There are no accounts. Your best score lives in your own browser\'s storage and is lost if you clear site data.'],
                    ['q' => 'Does it work on a phone?', 'a' => 'Yes. The grid resizes for small screens and the cards are sized for tapping.'],
                    ['q' => 'Is there a time limit?', 'a' => 'No. The timer records how long you took but nothing forces you to hurry.'],
                ],
            ],
        ],
        [
            'slug' => 'zipzap-game',
            'path' => '/zipzap-game',
            'name' => 'Zip Zap',
            'title' => 'Zip Zap - Free Browser Puzzle Game',
            'category' => 'game',
            'icon' => 'fas fa-bolt',
            'summary' => 'A quick reaction puzzle that gets harder as you go.',
            'keywords' => ['puzzle', 'reaction', 'browser game'],
            'content' => [
                'intro' => 'Zip Zap is a quick reaction puzzle. Patterns appear and you have to respond correctly before the next one arrives, with the pace increasing as you progress. Rounds are short, which makes it a reasonable way to spend two minutes rather than twenty.',
                'input' => 'Nothing to upload - just start a round.',
                'output' => 'A score for the round, with your best kept in your browser.',
                'steps' => [
                    'Start a round.',
                    'Respond to each pattern as it appears.',
                    'Keep up as the pace increases.',
                    'Compare your score against your own best.',
                ],
                'features' => [
                    'Difficulty that rises as you play',
                    'Score and best-score tracking',
                    'Touch and keyboard input',
                    'Runs in the browser with nothing to install',
                ],
                'sections' => [
                    ['heading' => 'Tips', 'body' => 'Accuracy beats speed early on. Getting into a rhythm matters more than rushing the first few rounds, because the pace increase punishes sloppy input later.'],
                ],
                'faqs' => [
                    ['q' => 'Does it need an internet connection?', 'a' => 'Only to load the page. After that the game runs on your device.'],
                    ['q' => 'Can I play on a phone?', 'a' => 'Yes, the controls accept touch.'],
                ],
            ],
        ],
        [
            'slug' => 'tractor-game',
            'path' => '/tractor-game',
            'name' => 'Tractor Farming',
            'title' => 'Tractor Farming - Free Browser Game',
            'category' => 'game',
            'icon' => 'fas fa-tractor',
            'summary' => 'Work the fields and beat your own best score.',
            'keywords' => ['farming game', 'browser game'],
            'content' => [
                'intro' => 'Tractor Farming is a light management game: work the fields, keep things moving, and build up a score across a session. It is intended as a short break rather than a long campaign.',
                'input' => 'Nothing to upload - choose a difficulty and begin.',
                'output' => 'A score for the session, with a best score and history kept in your browser.',
                'steps' => [
                    'Choose a difficulty.',
                    'Start the session and work the fields.',
                    'Keep going until the session ends.',
                    'Check the history panel to see how this run compared with earlier ones.',
                ],
                'features' => [
                    'Multiple difficulty levels',
                    'Score history kept in your browser',
                    'Touch and keyboard controls',
                    'No install and no account',
                ],
                'sections' => [
                    ['heading' => 'Tips', 'body' => 'Higher difficulties reward planning a route rather than reacting field by field. Watch where you will need to be next, not just where you are.'],
                ],
                'faqs' => [
                    ['q' => 'Is my progress saved?', 'a' => 'Your scores are kept in your browser\'s local storage. Clearing site data removes them.'],
                    ['q' => 'Does it work offline?', 'a' => 'Once the page has loaded, yes.'],
                ],
            ],
        ],
        [
            'slug' => 'highway-racer',
            'path' => '/highway-racer',
            'name' => 'Highway Racer',
            'title' => 'Highway Racer - Free Browser Driving Game',
            'category' => 'game',
            'icon' => 'fas fa-car-side',
            'summary' => 'Dodge traffic and keep your speed up for as long as you can.',
            'keywords' => ['racing game', 'driving', 'browser game'],
            'content' => [
                'intro' => 'Highway Racer is an endless driving game. Traffic comes towards you, your speed climbs the longer you survive, and the aim is simply to last. Three difficulty levels change how dense the traffic gets and how fast the speed ramps up.',
                'input' => 'Nothing to upload - pick a level and drive.',
                'output' => 'A distance and score for the run, with your best kept in your browser.',
                'steps' => [
                    'Choose easy, medium or hard.',
                    'Steer with the arrow keys, or by touching the left and right of the screen.',
                    'Weave through the traffic without hitting anything.',
                    'Survive as long as you can - your speed rises the longer you last.',
                ],
                'features' => [
                    'Three difficulty levels with different traffic density',
                    'Speed that increases with distance',
                    'Best score kept in your browser',
                    'Keyboard and touch controls',
                    'Pinch-zoom left enabled, with the canvas itself protected from stray gestures',
                ],
                'sections' => [
                    ['heading' => 'Tips', 'body' => 'Commit to a lane rather than hovering between two. Most crashes at speed come from changing your mind halfway through a gap that was actually fine.'],
                ],
                'faqs' => [
                    ['q' => 'What are the controls?', 'a' => 'Arrow keys on a keyboard, or tap the left and right sides of the screen on a touch device.'],
                    ['q' => 'Does the difficulty change anything real?', 'a' => 'Yes. It changes traffic density, starting speed and how quickly the speed ramps up.'],
                ],
            ],
        ],
        [
            'slug' => 'bike-racer',
            'path' => '/bike-racer',
            'name' => 'Bike Racer',
            'title' => 'Bike Racer - Free Browser Racing Game',
            'category' => 'game',
            'icon' => 'fas fa-motorcycle',
            'summary' => 'Weave through traffic on two wheels.',
            'keywords' => ['bike game', 'racing', 'browser game'],
            'content' => [
                'intro' => 'Bike Racer is the two-wheeled version of the same idea: thread through moving traffic for as long as you can. A bike is narrower than a car, so gaps that look impossible often are not - which is most of the appeal.',
                'input' => 'Nothing to upload - just start riding.',
                'output' => 'A distance and score for the run, kept as a personal best in your browser.',
                'steps' => [
                    'Start the run.',
                    'Steer with the arrow keys or by touching the screen.',
                    'Use the narrow profile of the bike to take gaps a car could not.',
                    'Keep going as the speed builds.',
                ],
                'features' => [
                    'Endless play with rising speed',
                    'Best score kept in your browser',
                    'Keyboard and touch controls',
                    'Loads and plays instantly',
                ],
                'sections' => [
                    ['heading' => 'Tips', 'body' => 'Small, early corrections work better than late ones. The bike responds quickly, so tapping repeatedly usually oversteers.'],
                ],
                'faqs' => [
                    ['q' => 'How do I steer?', 'a' => 'Arrow keys, or touch the left and right of the screen.'],
                    ['q' => 'Is there an end?', 'a' => 'No, it is endless - the aim is to beat your own distance.'],
                ],
            ],
        ],
        [
            'slug' => 'arrow-maze',
            'path' => '/arrow-maze',
            'name' => 'Arrow Maze',
            'title' => 'Arrow Maze - Free Browser Puzzle Game',
            'category' => 'game',
            'icon' => 'fas fa-arrows-turn-to-dots',
            'summary' => 'Navigate the maze one move at a time.',
            'keywords' => ['maze', 'puzzle', 'browser game'],
            'content' => [
                'intro' => 'Arrow Maze is a grid puzzle. You move one step at a time through a maze, working out a route to the exit. Later levels add more turns and dead ends, so it rewards looking ahead rather than moving first and thinking after.',
                'input' => 'Nothing to upload - pick up at the level you reached.',
                'output' => 'A completed level, with your progress kept in your browser.',
                'steps' => [
                    'Look over the grid before moving.',
                    'Move with the arrow keys, or with the on-screen arrow buttons.',
                    'Work your way to the exit.',
                    'Continue to the next level as they get larger.',
                ],
                'features' => [
                    'Progressive levels',
                    'On-screen arrow controls as well as keyboard',
                    'Progress kept in your browser',
                    'Touch-friendly controls',
                ],
                'sections' => [
                    ['heading' => 'Tips', 'body' => 'Trace the route backwards from the exit. Dead ends are much easier to spot working from the end than from the start.'],
                ],
                'faqs' => [
                    ['q' => 'Can I play without a keyboard?', 'a' => 'Yes. On-screen arrow buttons are provided for touch devices.'],
                    ['q' => 'Is my level progress saved?', 'a' => 'It is kept in your browser\'s storage, so it survives a reload but not clearing site data.'],
                ],
            ],
        ],
    ],
];
