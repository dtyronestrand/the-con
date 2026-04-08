## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2024-05-18 - Missing ID bindings in custom forms
**Learning:** Developers often remember to add `<label for="email">` but forget to add `id="email"` to the actual input field in custom-built forms. This completely breaks screen reader association.
**Action:** Always verify that custom form inputs explicitly bind their `id` to match the `for` attribute of their corresponding label, and include proper autocomplete/required attributes for basic accessibility.
