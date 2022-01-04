import React, { useState } from 'react';
import Button from '@/components/elements/Button';
import { Actions, useStoreActions } from 'easy-peasy';
import { ApplicationStore } from '@/state';
import installPlugin from '@/api/server/plugins/installPlugin';
import { ServerContext } from '@/state/server';
import { httpErrorToHuman } from '@/api/http';
import uninstallPlugin from '@/api/server/plugins/uninstallPlugin';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faDownload, faTrash } from '@fortawesome/free-solid-svg-icons';

interface Props {
    pluginId: string;
    installed: number;
}

export default ({ pluginId, installed }: Props) => {
    const uuid = ServerContext.useStoreState(state => state.server.data!.uuid);

    const [ disabled, setDisabled ] = useState(false);
    const [ isInstalled, setInstalled ] = useState(installed);

    const { addError, clearFlashes } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);

    const submit = () => {
        setDisabled(true);
        clearFlashes('server:plugins');

        if (isInstalled === 1) {
            uninstallPlugin(uuid, pluginId)
                .then(() => {
                    setInstalled(0);
                    setDisabled(false);
                })
                .catch(error => {
                    setDisabled(false);
                    addError({ key: 'server:plugins', message: httpErrorToHuman(error) });
                });
        } else {
            installPlugin(uuid, pluginId)
                .then(() => {
                    setInstalled(1);
                    setDisabled(false);
                })
                .catch(error => {
                    setDisabled(false);
                    addError({ key: 'server:plugins', message: httpErrorToHuman(error) });
                });
        }
    };

    return (
        <>
            <Button css={'float: right; margin-top: .8rem;'} isLoading={disabled} disabled={disabled} type={'button'} size={'xsmall'} color={(isInstalled === 1 ? 'red' : 'green')} onClick={submit}>
                <FontAwesomeIcon icon={isInstalled === 1 ? faTrash : faDownload}/> {isInstalled === 1 ? 'Uninstall' : 'Install'}
            </Button>
        </>
    );
};
