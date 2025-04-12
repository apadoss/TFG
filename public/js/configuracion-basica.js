document.addEventListener('DOMContentLoaded', function() {
    const anyOption = document.querySelectorAll('.option-any');

    anyOption.forEach(function(option) {
        option.addEventListener('change', function() {
            const groupName = this.id.split('-any')[0];

            const groupOptions = document.querySelectorAll(`.${groupName}-option`);

            if (this.checked) {
                groupOptions.forEach(function(groupOption) {
                    groupOption.disabled = true;
                    groupOption.checked = false;
                });
            } else {
                groupOptions.forEach(function(groupOption) {
                    groupOption.disabled = false;
                });
            }
        });
    });

    const allOptions = document.querySelectorAll('.computing-power-option, .purpose-option, .cpu-brand-option, .gpu-brand-option');
    allOptions.forEach(function(option) {
        option.addEventListener('change', function() {
            if (this.checked) {
                const groupName = this.id.split('-')[0] + (this.id.split('-')[1] === 'brand' ? '-brand' : '');

                const anyOption = document.querySelectorAll(`.${groupName}-any`);
                if (anyOption) {
                    anyOption.checked = false;
                }
            }
        });
    });
});