@extends("layouts.app")
@section("title", "Send SMS")
@section("content")
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Send SMS</h1>

    <form method="POST" action="{{ route("admin.communication.send") }}" class="bg-white rounded shadow p-6 space-y-6 max-w-4xl">
        @csrf

        <!-- AUDIENCE -->
        <div>
            <h3 class="font-bold text-blue-900 border-b pb-2 mb-3">Audience</h3>

            <select name="audience" id="audience" class="w-full border rounded px-3 py-2 mb-3" required>
                <option value="parents">All Parents</option>
                <option value="by_level">By Level (all parents of that level)</option>
                <option value="by_class">By Class</option>
                <option value="by_stream">By Class + Stream</option>
                <option value="staff">All Staff</option>
                <option value="custom">Custom Numbers</option>
            </select>

            <!-- LEVEL -->
            <div id="level-wrap" class="hidden mb-3">
                <label class="block font-semibold mb-1 text-sm">Level</label>
                <select name="level" id="level" class="w-full border rounded px-3 py-2">
                    <option value="">-- Select Level --</option>
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl }}">{{ \App\Services\Level\SchoolLevelService::label($lvl) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- CLASS -->
            <div id="class-wrap" class="hidden mb-3">
                <label class="block font-semibold mb-1 text-sm">Class</label>
                <select name="classroom_id" id="classroom_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Select Level First --</option>
                </select>
            </div>

            <!-- STREAM -->
            <div id="stream-wrap" class="hidden mb-3">
                <label class="block font-semibold mb-1 text-sm">Stream</label>
                <select name="stream" id="stream" class="w-full border rounded px-3 py-2">
                    <option value="">All Streams</option>
                </select>
            </div>

            <!-- CUSTOM PHONES -->
            <div id="phones-wrap" class="hidden mb-3">
                <label class="block font-semibold mb-1 text-sm">Custom Numbers (comma-separated)</label>
                <input type="text" name="phones" placeholder="+255712345678,+255787654321" class="w-full border rounded px-3 py-2">
            </div>

            <div class="bg-gray-100 border rounded p-3 text-sm">
                Recipients matched: <strong id="recipient-count">Calculating...</strong>
            </div>
        </div>

        <!-- MESSAGE -->
        <div>
            <h3 class="font-bold text-blue-900 border-b pb-2 mb-3">Message</h3>

            <textarea name="message" id="message" rows="5" maxlength="500" class="w-full border rounded px-3 py-2" required>{{ old("message") }}</textarea>

            <div class="grid grid-cols-4 gap-4 mt-3 text-sm">
                <div class="bg-gray-100 rounded p-3">
                    <div class="text-gray-500">Characters</div>
                    <div class="text-xl font-bold" id="char-count">0</div>
                </div>
                <div class="bg-gray-100 rounded p-3">
                    <div class="text-gray-500">SMS per recipient</div>
                    <div class="text-xl font-bold" id="units-count">1</div>
                </div>
                <div class="bg-gray-100 rounded p-3">
                    <div class="text-gray-500">Recipients</div>
                    <div class="text-xl font-bold" id="recipients-count">0</div>
                </div>
                <div class="bg-blue-100 rounded p-3 border-2 border-blue-900">
                    <div class="text-blue-900">Total units</div>
                    <div class="text-xl font-bold text-blue-900" id="total-units">0</div>
                </div>
            </div>

            <div id="warning" class="hidden bg-yellow-100 border-l-4 border-yellow-600 p-3 mt-3 text-sm">
                This message is over 160 characters. It will be split into multiple SMS and cost extra units per recipient.
            </div>
        </div>

        <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-800">Send SMS</button>
    </form>

    <script>
    (function () {
        var SINGLE_MAX = 160;
        var MULTI_MAX  = 153;

        var audienceSelect = document.getElementById("audience");
        var levelWrap      = document.getElementById("level-wrap");
        var classWrap      = document.getElementById("class-wrap");
        var streamWrap     = document.getElementById("stream-wrap");
        var phonesWrap     = document.getElementById("phones-wrap");

        var levelSelect    = document.getElementById("level");
        var classSelect    = document.getElementById("classroom_id");
        var streamSelect   = document.getElementById("stream");

        var messageArea    = document.getElementById("message");
        var charCount      = document.getElementById("char-count");
        var unitsCount     = document.getElementById("units-count");
        var recipientsEl   = document.getElementById("recipients-count");
        var recipientCount = document.getElementById("recipient-count");
        var totalUnits     = document.getElementById("total-units");
        var warningBox     = document.getElementById("warning");

        var endpoints = {
            classes: "{{ route("admin.api.comm.classrooms.byLevel") }}",
            streams: "{{ route("admin.api.comm.streams.byClass") }}",
            preview: "{{ route("admin.api.preview.recipients") }}"
        };

        function get(url) {
            return fetch(url, { headers: { "Accept": "application/json" } }).then(r => r.json());
        }

        // ============ AUDIENCE FIELD VISIBILITY ============
        function updateAudienceFields() {
            var v = audienceSelect.value;

            levelWrap.classList.toggle("hidden",   v !== "by_level" && v !== "by_class" && v !== "by_stream");
            classWrap.classList.toggle("hidden",   v !== "by_class" && v !== "by_stream");
            streamWrap.classList.toggle("hidden",  v !== "by_stream");
            phonesWrap.classList.toggle("hidden",  v !== "custom");

            refreshRecipients();
        }

        // ============ LEVEL -> CLASS ============
        levelSelect.addEventListener("change", function () {
            classSelect.innerHTML = "<option value=\"\">-- Loading --</option>";
            streamSelect.innerHTML = "<option value=\"\">All Streams</option>";

            if (!this.value) {
                classSelect.innerHTML = "<option value=\"\">-- Select Level First --</option>";
                refreshRecipients();
                return;
            }

            get(endpoints.classes + "?level=" + encodeURIComponent(this.value)).then(function (data) {
                if (!data.length) {
                    classSelect.innerHTML = "<option value=\"\">-- No classes --</option>";
                    refreshRecipients();
                    return;
                }
                var html = "<option value=\"\">-- Select Class --</option>";
                data.forEach(function (c) {
                    var label = c.stream ? c.name + " (" + c.stream + ")" : c.name;
                    html += "<option value=\"" + c.id + "\">" + label + "</option>";
                });
                classSelect.innerHTML = html;
                refreshRecipients();
            });
        });

        // ============ CLASS -> STREAM ============
        classSelect.addEventListener("change", function () {
            streamSelect.innerHTML = "<option value=\"\">-- Loading --</option>";

            if (!this.value) {
                streamSelect.innerHTML = "<option value=\"\">All Streams</option>";
                refreshRecipients();
                return;
            }

            get(endpoints.streams + "?classroom_id=" + this.value).then(function (data) {
                var html = "<option value=\"\">All Streams</option>";
                data.forEach(function (s) {
                    html += "<option value=\"" + s + "\">" + s + "</option>";
                });
                streamSelect.innerHTML = html;
                refreshRecipients();
            });
        });

        streamSelect.addEventListener("change", refreshRecipients);

        // ============ RECIPIENT PREVIEW ============
        function refreshRecipients() {
            var body = new FormData();
            body.append("audience", audienceSelect.value);
            body.append("level", levelSelect.value);
            body.append("classroom_id", classSelect.value);
            body.append("stream", streamSelect.value);

            fetch(endpoints.preview, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: body
            })
            .then(r => r.json())
            .then(data => {
                recipientsEl.textContent   = data.count || 0;
                recipientCount.textContent = data.count || 0;
                recalc();
            })
            .catch(() => {
                recipientsEl.textContent = "?";
                recipientCount.textContent = "?";
            });
        }

        // ============ CHARACTER & UNITS ============
        function unitsFor(len) {
            if (len === 0) return 0;
            if (len <= SINGLE_MAX) return 1;
            return Math.ceil(len / MULTI_MAX);
        }

        function recalc() {
            var len = messageArea.value.length;
            var units = unitsFor(len);
            var rec = parseInt(recipientsEl.textContent) || 0;
            var total = units * rec;

            charCount.textContent = len;
            unitsCount.textContent = units;
            totalUnits.textContent = total;

            if (len > SINGLE_MAX) {
                warningBox.classList.remove("hidden");
            } else {
                warningBox.classList.add("hidden");
            }
        }

        messageArea.addEventListener("input", recalc);
        audienceSelect.addEventListener("change", updateAudienceFields);

        // Init
        updateAudienceFields();
        recalc();
    })();
    </script>
@endsection