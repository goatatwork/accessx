# accessx

**v0.2.3 - 20210804**
- Read-only aggregators: The previously unused `manage_network` permission is now being utilized on the Aggregators pages to grant access to non-read-only features. The `view_network` permission continues to be used to grant access to the Aggregators pages as a whole.
- The customers index page now has a search function and a link to resort based on whether the customer has any suspended services.

---
**v0.2.2 - 20210730**
- Selecting a speed package is now required when creating a new provisioning record.
- The email and phone fields are no longer required when adding a customer.

---
**v0.2.1 - 20210722**
- The update adds the following directories to the list of directories to be excluded from backups. 
   - `base_path('backups')`
   - `base_path('system')`

---
**v0.2 - 20210722**
- The subscriber_id will now be automatically updated on the switch when the SetSubscriberId job is dispatched. That job talks to accessr's API to tell it to set the subscriber_id for the port being provisioned. accessr then connects to the switch to set the subscriber_id. There are two scenarios that cause the job to be dispatched. The first is when a provisioning record is created. The second is when a provisioning record's port assignment (`port_id`) is changed.

---
**v0.1**
- Hello world.