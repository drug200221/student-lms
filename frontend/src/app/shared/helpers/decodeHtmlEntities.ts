export function decodeHtmlEntities(encodedString: string) {
  const textarea = document.createElement('textarea');
  textarea.innerHTML = encodedString;
  return textarea.value;
}
