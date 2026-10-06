const fs = require('fs');

const md = fs.readFileSync('LAPORAN_KP_BOSQ.md', 'utf8');

// Parse markdown tables
function parseTable(block) {
  const lines = block.trim().split('\n');
  if (lines.length < 2) return block;
  let html = '<table>\n';
  lines.forEach((line, i) => {
    if (line.match(/^\|[-\s|]+\|$/)) return; // skip separator
    const cells = line.replace(/^\||\|$/g, '').split('|').map(c => c.trim());
    const tag = i === 0 ? 'th' : 'td';
    html += '<tr>' + cells.map(c => `<${tag}>${c}</${tag}>`).join('') + '</tr>\n';
  });
  html += '</table>\n';
  return html;
}

// Process line by line
const lines = md.split('\n');
let result = [];
let inMermaid = false, inCode = false, inTable = false;
let tableLines = [];
let codeLines = [];
let mermaidLines = [];

for (let i = 0; i < lines.length; i++) {
  const line = lines[i];
  
  if (line.startsWith('```mermaid')) { inMermaid = true; mermaidLines = []; continue; }
  if (inMermaid && line.startsWith('```')) {
    result.push('<pre class="mermaid">' + mermaidLines.join('\n') + '</pre>');
    inMermaid = false; continue;
  }
  if (inMermaid) { mermaidLines.push(line); continue; }
  
  if (line.startsWith('```')) { inCode = !inCode; codeLines = []; continue; }
  if (inCode) { codeLines.push(line); continue; }
  
  if (line.startsWith('|')) {
    if (!inTable) { inTable = true; tableLines = []; }
    tableLines.push(line);
    continue;
  } else if (inTable) {
    result.push(parseTable(tableLines.join('\n')));
    inTable = false; tableLines = [];
  }
  
  let out = line;
  out = out.replace(/^#### (.+)$/, '<h4>$1</h4>');
  out = out.replace(/^### (.+)$/, '<h3>$1</h3>');
  out = out.replace(/^## (.+)$/, '<h2>$1</h2>');
  out = out.replace(/^# (.+)$/, '<h1>$1</h1>');
  out = out.replace(/^---$/, '<hr>');
  out = out.replace(/^\d+\. (.+)$/, '<li>$1</li>');
  out = out.replace(/\*\*\*(.+?)\*\*\*/g, '<strong><em>$1</em></strong>');
  out = out.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
  out = out.replace(/\*(.+?)\*/g, '<em>$1</em>');
  out = out.replace(/`(.+?)`/g, '<code>$1</code>');
  if (out.trim() === '') out = '<br>';
  result.push(out);
}
if (inTable) result.push(parseTable(tableLines.join('\n')));

const body = result.join('\n');

const fullHtml = `<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Kerja Praktik - BOS'Q</title>
<style>
  @page { margin: 2.5cm; size: A4; }
  body { font-family: "Times New Roman", Times, serif; font-size: 12pt; line-height: 1.8; color: #000; max-width: 900px; margin: 0 auto; padding: 20px 40px; }
  h1 { font-size: 16pt; text-align: center; font-weight: bold; page-break-before: auto; margin-top: 50px; margin-bottom: 20px; }
  h2 { font-size: 14pt; font-weight: bold; margin-top: 30px; margin-bottom: 15px; }
  h3 { font-size: 13pt; font-weight: bold; margin-top: 25px; }
  h4 { font-size: 12pt; font-weight: bold; margin-top: 20px; }
  p { text-align: justify; margin: 10px 0; }
  table { border-collapse: collapse; width: 100%; margin: 20px 0; font-size: 11pt; }
  td, th { border: 1px solid #000; padding: 6px 10px; vertical-align: top; }
  th { background-color: #d0d0d0; font-weight: bold; text-align: center; }
  hr { border: none; border-top: 1px solid #000; margin: 40px 0; page-break-after: always; }
  pre { background: #f8f8f8; border: 1px solid #ccc; padding: 12px; font-size: 9pt; overflow-x: auto; white-space: pre-wrap; font-family: Consolas, monospace; }
  code { font-family: Consolas, monospace; background: #f0f0f0; padding: 1px 4px; font-size: 10pt; }
  li { margin: 5px 0; }
  em { font-style: italic; }
  strong { font-weight: bold; }
  .page-break { page-break-before: always; }
</style>
</head>
<body>
${body}
</body>
</html>`;

fs.writeFileSync('LAPORAN_KP_BOSQ.html', fullHtml, 'utf8');
console.log('HTML generated: LAPORAN_KP_BOSQ.html');
console.log('Size: ' + Math.round(fs.statSync('LAPORAN_KP_BOSQ.html').size / 1024) + ' KB');
