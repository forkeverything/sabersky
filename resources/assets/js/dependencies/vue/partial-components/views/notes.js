Vue.component('notes', {
    name: 'Notes',
    template: '<div class="notes">' +
    '<form-errors></form-errors>' +
    '<form class="form-post-note" @submit.prevent="add">' +
    '<div class="form-group">' +
    '<textarea class="form-control autosize" placeholder="Add a new note..." v-model="content"></textarea>' +
    '</div>' +
    '<div class="form-group align-end">' +
    '<button type="submit" class="btn btn-solid-blue btn-small" :disabled="! content">Post</button>' +
    '</div>' +
    '</form>' +
    '<div class="existing-notes" v-show="notes.length > 0">' +
    '<ul class="list-unstyled list-notes">' +
    '<li v-for="note in notes" class="single-note">' +
    '<a v-if="canDelete(note)" @click="deleteNote(note)" class="btn-close small"><i class="fa fa-close"></i></a>' +
    '<div class="notes-meta">' +
    '<span class="poster">{{ note.poster.name }}</span><span class="posted">{{ note.created_at | diffHuman }}</span>' +
    '</div>' +
    '<p class="content">{{ note.content }}</p>' +
    '</li>' +
    '</ul>' +
    '</div>' +
    '</div>',
    data: function () {
        return {
            ajaxReady: true,
            notes: [],
            content: ''
        };
    },
    props: ['subject', 'subject_id'],
    computed: {
        url: function () {
            return '/notes/' + this.subject + '/' + this.subject_id;
        }
    },
    methods: {
        fetch: function () {
            $.get(this.url, function (data) {
                this.notes = data;
            }.bind(this));
        },
        add: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: this.url,
                method: 'POST',
                data: {
                    "content": self.content
                },
                success: function (data) {
                    // success
                    self.content = '';
                    self.notes.unshift(data);
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        },
        canDelete: function (note) {
            if (!this.user) return false;
            if (this.user.role.position === 'admin') return true;
            return this.user.id === note.user_id;
        },
        deleteNote: function (note) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: self.url + '/' + note.id,
                method: 'DELETE',
                success: function (data) {
                    // success
                    self.notes = _.reject(self.notes, note);
                    self.ajaxReady = true;
                },
                error: function (response) {
                    self.ajaxReady = true;
                }
            });
        }
    },
    mixins: [userCompany],
    events: {},
    ready: function () {
        this.fetch();
    }
});