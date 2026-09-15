## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2026-09-15 - Native Checkbox Keyboard Accessibility
## 2026-09-15 - Native Checkbox Keyboard Accessibility
**Learning:** Using display: none on native input checkboxes completely breaks screen reader access and keyboard navigation (tabbing), rendering them inaccessible.
**Action:** Instead of display: none, apply Tailwinds sr-only and peer classes to the native input, and style a sibling visual element using peer-focus-visible:ring-2 to provide focus indicators while preserving full keyboard accessibility.
