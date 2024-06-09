import axios from 'axios'

export async function fetchLinkedOrgs ({ commit }, { resourceType, resourceUuid }) {
  const { data } = await axios.get('/api/organizations', { params: {
    'resource_type': resourceType,
    'resource_uuid': resourceUuid,
    'resource_query': 'linked'
  } })
  commit('SET_LINKED_ORGS', { orgs: data.data })
}

export async function fetchUnlinkedOrgs ({ commit }, { resourceType, resourceUuid }) {
  const { data } = await axios.get('/api/organizations', { params: {
    'resource_type': resourceType,
    'resource_uuid': resourceUuid,
    'resource_query': 'unlinked'
  } })
  commit('SET_UNLINKED_ORGS', { orgs: data.data })
}
