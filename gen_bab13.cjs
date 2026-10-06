const fs = require('fs');

const md = fs.readFileSync('LAPORAN_KP_BAB1-3.md', 'utf8');

function escapeHtml(str) {
  return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function parseTable(block) {
  const lines = block.trim().split('\n').filter(l => l.trim());
  if (lines.length < 1) return '';
  let html = '<table>\n';
  let isHeader = true;
  lines.forEach((line) => {
    if (line.match(/^\|[\s-|]+\|$/)) { isHeader = false; return; }
    const cells = line.replace(/^\||\|$/g, '').split('|').map(c => c.trim());
    const tag = isHeader ? 'th' : 'td';
    html += '<tr>' + cells.map(c => `<${tag}>${inlineFormat(c)}</${tag}>`).join('') + '</tr>\n';
    if (isHeader) isHeader = false;
  });
  html += '</table>\n';
  return html;
}

function inlineFormat(str) {
  return str
    .replace(/\*\*\*(.+?)\*\*\*/g, '<strong><em>$1</em></strong>')
    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
    .replace(/\*(.+?)\*/g, '<em>$1</em>')
    .replace(/`(.+?)`/g, '<code>$1</code>');
}

const rawLines = md.split('\n');
let result = [];
let inCode = false, inTable = false;
let tableLines = [], codeLines = [], codeLang = '';
let listOpen = false;

for (let i = 0; i < rawLines.length; i++) {
  const line = rawLines[i];

  // Code block
  if (line.startsWith('```')) {
    if (!inCode) {
      inCode = true; codeLang = line.slice(3).trim(); codeLines = [];
    } else {
      const escaped = codeLines.map(escapeHtml).join('\n');
      result.push(`<pre><code class="language-${codeLang}">${escaped}</code></pre>`);
      inCode = false;
    }
    continue;
  }
  if (inCode) { codeLines.push(line); continue; }

  // Table
  if (line.startsWith('|')) {
    if (!inTable) { inTable = true; tableLines = []; }
    tableLines.push(line);
    continue;
  } else if (inTable) {
    if (listOpen) { result.push('</ul>'); listOpen = false; }
    result.push(parseTable(tableLines.join('\n')));
    inTable = false; tableLines = [];
  }

  // Headings
  const h4 = line.match(/^#### (.+)/);
  const h3 = line.match(/^### (.+)/);
  const h2 = line.match(/^## (.+)/);
  const h1 = line.match(/^# (.+)/);
  if (h4) { result.push(`<h4>${inlineFormat(h4[1])}</h4>`); continue; }
  if (h3) { result.push(`<h3>${inlineFormat(h3[1])}</h3>`); continue; }
  if (h2) { result.push(`<h2>${inlineFormat(h2[1])}</h2>`); continue; }
  if (h1) { result.push(`<h1>${inlineFormat(h1[1])}</h1>`); continue; }
  if (line.trim() === '---') { result.push('<hr>'); continue; }

  // Ordered list
  const olMatch = line.match(/^(\d+)\. (.+)/);
  if (olMatch) {
    result.push(`<li>${inlineFormat(olMatch[2])}</li>`);
    continue;
  }

  // Unordered list
  const ulMatch = line.match(/^- (.+)/);
  if (ulMatch) {
    if (!listOpen) { result.push('<ul>'); listOpen = true; }
    result.push(`<li>${inlineFormat(ulMatch[1])}</li>`);
    continue;
  } else if (listOpen && line.trim() !== '') {
    result.push('</ul>'); listOpen = false;
  }

  // Empty line
  if (line.trim() === '') { result.push(''); continue; }

  // Bold italic prefix like **[SISIPKAN SCREENSHOT: ...]**
  const out = inlineFormat(line);
  result.push(out);
}
if (inTable) result.push(parseTable(tableLines.join('\n')));
if (listOpen) result.push('</ul>');

// Wrap paragraphs
let body = '';
let paras = result.join('\n').split('\n\n');
paras.forEach(block => {
  const t = block.trim();
  if (!t) return;
  if (t.startsWith('<')) { body += t + '\n\n'; }
  else { body += '<p>' + t.replace(/\n/g, ' ') + '</p>\n\n'; }
});

const fullHtml = `<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan KP BOS'Q - BAB I, II, III</title>
<style>
  body {
    font-family: "Times New Roman", Times, serif;
    font-size: 12pt;
    line-height: 2;
    color: #000;
    max-width: 820px;
    margin: 0 auto;
    padding: 30px 50px;
  }
  h1 {
    font-size: 16pt;
    text-align: center;
    font-weight: bold;
    margin-top: 60px;
    margin-bottom: 25px;
    page-break-before: auto;
  }
  h2 { font-size: 14pt; font-weight: bold; margin-top: 35px; margin-bottom: 15px; }
  h3 { font-size: 13pt; font-weight: bold; margin-top: 28px; margin-bottom: 12px; }
  h4 { font-size: 12pt; font-weight: bold; margin-top: 22px; margin-bottom: 10px; }
  p { text-align: justify; margin: 8px 0 12px 0; text-indent: 1.5em; }
  table {
    border-collapse: collapse;
    width: 100%;
    margin: 20px 0;
    font-size: 11pt;
    page-break-inside: avoid;
  }
  td, th {
    border: 1px solid #000;
    padding: 6px 10px;
    vertical-align: top;
  }
  th {
    background-color: #c8c8c8;
    font-weight: bold;
    text-align: center;
  }
  tr:nth-child(even) td { background-color: #f9f9f9; }
  hr {
    border: none;
    border-top: 2px solid #000;
    margin: 50px 0;
  }
  pre {
    background: #f5f5f5;
    border: 1px solid #bbb;
    padding: 12px;
    font-size: 9pt;
    white-space: pre-wrap;
    word-break: break-all;
    font-family: Consolas, "Courier New", monospace;
    border-radius: 4px;
  }
  code {
    font-family: Consolas, "Courier New", monospace;
    background: #f0f0f0;
    padding: 1px 5px;
    border: 1px solid #ddd;
    font-size: 10pt;
    border-radius: 3px;
  }
  li { margin: 5px 0; }
  ul, ol { margin: 10px 0 15px 25px; }
  em { font-style: italic; }
  strong { font-weight: bold; }
  .screenshot-placeholder {
    background: #fffbcc;
    border: 2px dashed #f0a000;
    padding: 12px 18px;
    margin: 15px 0;
    font-style: italic;
    color: #7a5c00;
    border-radius: 4px;
    font-size: 10.5pt;
  }
  .page-num {
    text-align: center;
    color: #888;
    font-size: 10pt;
    margin-top: 8px;
  }
</style>
</head>
<body>
${body.replace(/<strong>\[SISIPKAN SCREENSHOT: (.*?)\]<\/strong>/g, '<div class="screenshot-placeholder">📷 [SISIPKAN SCREENSHOT: $1]</div>')}
</body>
</html>`;

fs.writeFileSync('LAPORAN_KP_BAB1-3.html', fullHtml, 'utf8');
console.log('Done: LAPORAN_KP_BAB1-3.html - ' + Math.round(fs.statSync('LAPORAN_KP_BAB1-3.html').size / 1024) + ' KB');
