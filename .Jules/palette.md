## 2024-04-03 - Accessible Toolbars
**Learning:** Hover-only toolbars (`group-hover:opacity-100`) make interactive elements completely invisible to keyboard-only users navigating via tab. This is a severe a11y trap.
**Action:** Always add `focus-within:opacity-100` alongside `group-hover` styles for any toolbar or container with hidden interactive elements, and ensure inner buttons have visible focus rings (`focus-visible:ring-2`) and `aria-label`s.
