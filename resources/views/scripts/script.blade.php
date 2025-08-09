 <script>
     document.querySelector('#costEstimation').addEventListener('click', function() {
         const data = {
             length: document.querySelector('input[name="length"]').value,
             width: document.querySelector('input[name="width"]').value,
             height: document.querySelector('input[name="height"]').value,
             dimension_unit: document.querySelector('select[aria-label="Size unit"]').value,
             weight: document.querySelector('input[name="weight"]').value,
             weight_unit: document.querySelector('select[aria-label="Weight unit"]').value,
         };

         fetch('/calculate-shipping', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf_token"]').content
                 },
                 body: JSON.stringify(data)
             })
             .then(res => res.json())
             .then(res => {
                 console.log(res);
                 const container = document.querySelector('.result');
                 container.innerHTML = '';

                 // --- Best Price Card ---
                 if (res.best_price) {
                     container.innerHTML += `
                            <div style="
                    background: #f0fff4;
                    border: 2px solid #38a169;
                    border-radius: 12px;
                    padding: 20px;
                    margin-bottom: 20px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                    font-family: Arial, sans-serif;
                ">
                                <h2 style="color:#2f855a; font-size: 1.3em; margin-bottom: 10px;">🏆 Best Price</h2>
                                <p style="margin: 4px 0;"><strong>Carrier:</strong> ${res.best_price.carrier}</p>
                                <p style="margin: 4px 0;"><strong>Transit Time:</strong> ${res.best_price.transit_time}</p>
                                <p style="margin: 4px 0; font-size: 1.4em; font-weight: bold; color:#2f855a;">
                                    ${res.best_price.currency} ${res.best_price.rate}
                                </p>
                            </div>
                        `;
                 }

                 // --- Other Estimates Cards ---
                 if (res.best_estimates && res.best_estimates.length) {
                     container.innerHTML +=
                         `<h3 style="font-family: Arial, sans-serif; margin-bottom: 10px;">📦 Other Estimates</h3>`;
                     container.innerHTML +=
                         `<div style="display: grid; gap: 20px; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));">`;

                     res.best_estimates.forEach(r => {
                         container.innerHTML += `
                                <div style="
                        background: white;
                        border-radius: 10px;
                        padding: 15px;
                        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                        font-family: Arial, sans-serif;
                        border: 1px solid #eee;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 8px 15px rgba(0,0,0,0.1)'"
                                   onmouseout="this.style.transform='';this.style.boxShadow='0 4px 10px rgba(0,0,0,0.05)'">
                                    <p style="font-weight:bold; font-size: 1.1em;">${r.carrier}</p>
                                    <p style="color: #555;">Transit: ${r.transit_time}</p>
                                    <p style="margin-top:8px; font-size: 1.2em; color: #2b6cb0; font-weight: bold;">
                                        ${r.currency} ${r.rate}
                                    </p>
                                </div>
                            `;
                     });

                     container.innerHTML += `</div>`;

                     container.innerHTML += `
                            <h1 class='text-3xl mt-2'>Marketsz Costs Include:</h1>
                            <ul class="list-disc">
                                <li>
                                    <p>
                                        Simplified shipping rates based on weight
                                        <p>
                                    </li>
                                <li>
                                    <p>
                                        FREE package storage and consolidation
                                        <p>
                                    </li>
                                <li>
                                    <p>
                                        Deep discounts with global carrier partners
                                        <p>
                                    </li>
                                <li>
                                    <p>
                                        Customs documentation completion
                                        <p>
                                    </li>
                                </ul>
                                <p>
                                    *This amount is only an estimate. It displays the base rate for this weight. Surcharges may apply due to size, commodity, and delivery address details and will be included in the final shipping charge.</p>
                                <p>
                                    Excludes oversized shipments and palletized shipments with linear dimensions greater than 72 inches (183 cm).​
                                </p>
                                <p>
                                   Rates quoted above are inclusive of any applicable package consolidation and preparation for export.
                                </p>
                        `
                 } else {
                     container.innerHTML += `
                            <div style="padding:20px; background:#ffecec; color:#a94442; border:1px solid #f5c2c2; border-radius:8px;">
                                No shipping options available for the given dimensions and weight.
                            </div>
                        `;
                 }

                 // --- Note ---
                 if (res.note) {
                     container.innerHTML +=
                         `<p style="margin-top: 15px; font-size: 0.9em; color:#666; font-style: italic;">${res.note}</p>`;
                 }
             });
     });
 </script>
